<?php

namespace App\Http\Controllers\Api\Core;

use App\Events\Core\Auth\VerificationEmailEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Core\Auth\{
    ChangeEmailRequest,
    ChangePasswordRequest,
    ForgotPasswordRequest,
    LoginRequest,
    RegisterFirstStepRequest,
    RegisterLastStepRequest,
    SiteParamsFirstStepRequest,
    SiteParamsLastStepRequest,
    VerificationCodeRequest
};
use App\Http\Resources\Core\UserResource;
use App\Models\GeneralSetting;
use App\Models\Role;
use App\Models\User;
use App\Traits\Core\Api\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\{
    Auth,
    DB,
    Hash,
    Log,
    RateLimiter
};
use Otp;
use Throwable;

class AuthController extends Controller
{
    use ResponseTrait;

    private Otp $otp;

    public function __construct()
    {
        $this->otp = new Otp();
    }

    // =======================
    // ====== LOGIN ==========
    // =======================
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $rateLimiterKey = $this->getRateLimiterKey($request->ip(), $credentials['email']);

        if ($response = $this->checkRateLimit($rateLimiterKey)) {
            return $response;
        }

        try {
            $user = User::with(['roles', 'person', 'avatar'])
                ->where('email', $credentials['email'])
                ->first();

            if (!$user || !Auth::attempt($credentials)) {
                RateLimiter::hit($rateLimiterKey, 60);
                return $this->responseError(
                    'login',
                    __('forms.login.errors.invalid_credentials'),
                    'unauthenticated',
                    401
                );
            }

            RateLimiter::clear($rateLimiterKey);

            return $this->buildAuthSuccessResponse($user, 'login');
        } catch (Throwable $e) {
            $this->logError($e, 'Login error', ['email' => $credentials['email'] ?? null]);
            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    // =======================
    // ===== REGISTER ========
    // =======================
    public function registerFirstStep(RegisterFirstStepRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            return DB::transaction(function () use ($data) {
                $user = User::create([
                    'email' => strtolower($data['email']),
                    'password' => Hash::make($data['password']),
                ]);


                $defaultRoleSlugs = [config('defaultRole.default_role_slug', 'user')];
                $user->roles()->attach(Role::whereIn('slug', $defaultRoleSlugs)->get());

                $this->sendVerificationCodeToUser($user);

                return $this->responseSuccess(
                    'register',
                    $user->id,
                    ['message' => __('forms.register.responses.new_code')],
                    meta: ['step' => 1]
                );
            });
        } catch (Throwable $e) {
            $this->logError($e, 'Register first step error', ['email' => $data['email'] ?? null]);
            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    public function registerLastStep(RegisterLastStepRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            if (!$this->validateOtp($data['email'], $data['code'])) {
                return $this->responseError(
                    'register',
                    __('forms.register.errors.verification_code'),
                    'Invalid verification code',
                    422
                );
            }

            $user = User::whereEmail($data['email'])->firstOrFail();

            return $this->buildAuthSuccessResponse($user, 'register');
        } catch (Throwable $e) {
            $this->logError($e, 'Register last step error', ['email' => $request->input('email')]);
            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    public function sendVerificationCode(VerificationCodeRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $user = User::where('email', $data['email'])->firstOrFail();
            $this->sendVerificationCodeToUser($user);

            return $this->responseSuccess('authentication', null, [
                'message' => __('forms.register.responses.new_code')
            ]);
        } catch (Throwable $e) {
            $this->logError($e, 'Send verification code error', ['email' => $request->input('email')]);
            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    // =======================
    // ==== FORGOT PASSWORD ==
    // =======================
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $user = User::whereEmail($data['email'])->firstOrFail();

            if (!$this->validateOtp($data['email'], $data['code'])) {
                return $this->responseError(
                    'forgot_password',
                    __('forms.register.errors.verification_code'),
                    'verification code',
                    422
                );
            }

            $user->update(['password' => Hash::make($data['password'])]);

            return $this->buildAuthSuccessResponse($user, 'forgot_password');
        } catch (Throwable $e) {
            $this->logError($e, 'Forgot password error', ['email' => $request->input('email')]);
            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    // =======================
    // ===== CHANGE PASSWORD ==
    // =======================
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $rateLimiterKey = $this->getRateLimiterKey($request->ip(), (string) $user->id);

            if ($response = $this->checkRateLimit($rateLimiterKey)) {
                return $response;
            }

            $data = $request->validated();
            $isSuperAdmin = $user->roles->contains('slug', 'super_admin');

            if (!$isSuperAdmin && !Hash::check($data['old_pwd'], $user->password)) {
                RateLimiter::hit($rateLimiterKey);
                return $this->responseError(
                    'authentication',
                    __('forms.change_password.errors.invalid_current'),
                    'unauthenticated',
                    401
                );
            }

            RateLimiter::clear($rateLimiterKey);

            $user->update(['password' => Hash::make($data['pwd'])]);

            return $this->responseSuccess(
                'change_password',
                $user->id,
                ['message' => __('forms.change_password.responses.success')]
            );
        } catch (Throwable $e) {
            $this->logError($e, 'Change password error', ['user_id' => Auth::id()]);
            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    // =======================
    // ===== CHANGE EMAIL =====
    // =======================
    public function changeEmail(ChangeEmailRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $user = Auth::user();
            $oldEmail = $user->email;

            $user->update(['email' => $data['new_mail']]);

            $user->currentAccessToken()?->delete();

            Log::info('User email changed successfully', [
                'user_id' => $user->id,
                'mail' => $oldEmail,
                'new_mail' => $user->email,
            ]);

            return $this->responseSuccess('authentication', $user->id, [
                'message' => __('forms.change_mail.responses.success')
            ]);
        } catch (Throwable $e) {
            $this->logError($e, 'Change email error', ['user_id' => Auth::id()]);
            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    // =======================
    // ===== LOGOUT ==========
    // =======================
    public function logout(): JsonResponse
    {
        try {
            $user = Auth::user();
            $user?->currentAccessToken()?->delete();

            return $this->responseSuccess('authentication', $user?->id, [
                'message' => __('api.responses.logout')
            ]);
        } catch (Throwable $e) {
            $this->logError($e, 'Logout error', ['user_id' => Auth::id()]);
            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    public function logoutAllDevices(): JsonResponse
    {
        try {
            $user = Auth::user();
            $user?->tokens()->delete();

            return $this->responseSuccess('authentication', $user?->id, [
                'message' => __('api.responses.logout_all_devices')
            ]);
        } catch (Throwable $e) {
            $this->logError($e, 'Logout all devices error', ['user_id' => Auth::id()]);
            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    // =======================
    // ===== REFRESH TOKEN ===
    // =======================
    public function refreshToken(): JsonResponse
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return $this->responseError('authentication', 'Unauthenticated', 'unauthenticated', 401);
            }

            $user->currentAccessToken()?->delete();

            $plainTextToken = $this->createUserToken($user);

            return $this->responseSuccess('authentication', $user->id, [
                'token' => $plainTextToken,
                'user'  => new UserResource($user),
            ]);
        } catch (Throwable $e) {
            $this->logError($e, 'Token refresh error', ['user_id' => Auth::id()]);
            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    // =======================
    // ===== ACCOUNT STATUS ===
    // =======================
    public function toggleAccountStatus(): JsonResponse
    {
        try {
            $user = Auth::user();
            $newStatus = !$user->is_active;
            $user->update(['is_active' => $newStatus]);
            if (!$newStatus) $user->currentAccessToken()?->delete();

            return $this->responseSuccess('account', $user->id, [
                'message' => $newStatus
                    ? __('api.responses.account_activated')
                    : __('api.responses.account_deactivated'),
                'is_active' => $newStatus,
            ]);
        } catch (Throwable $e) {
            $this->logError($e, 'Toggle account status error', ['user_id' => Auth::id()]);
            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    // =======================
    // ===== SITE PARAMETERS ==
    // =======================
    public function siteParamsFirstStep(SiteParamsFirstStepRequest $request): JsonResponse
    {
        try {
            $rateLimiterKey = $this->getRateLimiterKey($request->ip(), $request->email);

            if ($response = $this->checkRateLimit($rateLimiterKey)) return $response;

            $credentials = $request->validated();
            if (!Auth::attempt($credentials)) {
                RateLimiter::hit($rateLimiterKey);
                return $this->responseError(
                    'authentication',
                    __('forms.login.errors.invalid_credentials'),
                    'unauthenticated',
                    401
                );
            }

            RateLimiter::clear($rateLimiterKey);

            $user = Auth::user();
            if (!$user->roles->contains('slug', 'super_admin')) {
                return $this->responseError(
                    'authorization',
                    __('forms.site_parameters.errors.no_access'),
                    'forbidden',
                    403
                );
            }

            return $this->buildAuthSuccessResponse($user, 'authorization');
        } catch (Throwable $e) {
            $this->logError($e, 'Site params first step error', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
            ]);
            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    public function siteParamsLastStep(SiteParamsLastStepRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $generalSettings = GeneralSetting::firstOrFail();
            $generalSettings->update($data);

            return $this->responseSuccess('site_parameters', null, [
                'message' => __('forms.site_parameters.responses.success')
            ]);
        } catch (Throwable $e) {
            $this->logError($e, 'Site params last step error', ['user_id' => auth()->id()]);
            return $this->responseError(__('forms.common.errors.default'), 500);
        }
    }

    // =======================
    // ===== HELPER METHODS ==
    // =======================
    private function getRateLimiterKey(string $ip, string $email): string
    {
        return 'login:' . sha1($ip . '|' . strtolower($email));
    }

    private function checkRateLimit(string $key, int $maxAttempts = 5, int $decaySeconds = 60): ?JsonResponse
    {
        return RateLimiter::tooManyAttempts($key, $maxAttempts)
            ? $this->responseError('authentication', __('forms.login.errors.too_many_attempts'), 'rate_limited', 429)
            : null;
    }

    private function buildAuthSuccessResponse(User $user, string $type): JsonResponse
    {
        if (config('defaultRole.delete_previous_access_tokens_on_login', false)) {
            $user->tokens()->delete();
        }

        $plainTextToken = $this->createUserToken($user);
        $user->loadMissing(['person', 'avatar']);

        return $this->responseSuccess($type, $user->id, [
            'user' => new UserResource($user),
            'token' => $plainTextToken,
            'abilities' => $user->roles->pluck('slug')->all(),
        ]);
    }

    private function createUserToken(User $user): string
    {
        return $user->createToken('API Token (' . request()->userAgent() . ')', $user->roles->pluck('slug')->all())
            ->plainTextToken;
    }

    private function validateOtp(string $email, string $code): bool
    {
        return $this->otp->validate($email, $code)->status;
    }

    private function sendVerificationCodeToUser(User $user): void
    {
        event(new VerificationEmailEvent($user));
    }

    private function logError(Throwable $e, string $context, array $extra = []): void
    {
        Log::error($context, array_merge([
            'user_id' => Auth::id() ?? null,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], $extra));
    }
}
