<?php

namespace App\Livewire\Core\SuperAdmin\SiteParameters;

use App\Livewire\Forms\Core\SiteParameters\LastStepForm;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Symfony\Component\Process\Process;

class LastStep extends Component
{


    public GeneralSetting $generalSettings;
    public LastStepForm $form;


    public function downloadDatabase()
    {


        try {

            if (Storage::disk('local')->exists(config('app.name'))) {
                Storage::deleteDirectory(config('app.name'));
            }

            $projectPath = base_path(); // This fetches the root path of your Laravel application
             $phpPath = PHP_BINARY;

             //✅ Wrap path in quotes (important for Windows paths with spaces)
            $command = "\"{$phpPath}\" {$projectPath}/artisan backup:run";

            // Execute the command to perform the backup
            $process = Process::fromShellCommandline($command);
            $process->run();

            // Check if the process ran successfully
            if (!$process->isSuccessful()) {
                throw new \Exception('Backup command failed: ' . $process->getErrorOutput());
            }

            // Debugging: log the output
            Log::info($process->getOutput());

            // Retrieve the latest backup file
            $backupFiles = Storage::disk('local')->files(config('app.name'));
            $latestBackup = end($backupFiles); // Assuming the latest backup is the last file

            if (!$latestBackup) {
                throw new \Exception('No backup files found.');
            }

            // Provide a download link for the latest backup and delete after download
            return Storage::download($latestBackup);

        } catch (\Exception $e) {
            // Handle exceptions
            Log::error('Download Database error: ' . $e->getMessage());
            $this->dispatch('open-errors', __('forms.common.errors.default'));
        }
    }


    public function mount()
    {

        $this->dispatch('site-params-multi-form-clear', 'site-params-multi-form-step' );
            try {
                $this->generalSettings = GeneralSetting::firstOrFail();
                $this->form->fill([
                    'maintenance' => $this->generalSettings->maintenance,
                ]);

            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                $this->dispatch('open-errors', [$e->getMessage()]);
            }

    }

    public function handleSubmit()
    {

        $this->dispatch('form-submitted','.form-sp-l');
        $response =  $this->form->save($this->generalSettings);
       if ($response['status']) {
        $this->dispatch('open-toast', $response['message']);
       }else{
         $this->dispatch('open-errors', $response['errors']);
         }
    }

    public function render()
    {
        return view('livewire.core.super-admin.site-parameters.last-step');
    }
}
