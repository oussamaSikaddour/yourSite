{{-- resources/views/livewire/core/admin-color-picker.blade.php --}}

<div class="admin__color__picker__container">
  {{-- IMPORTANT: pass RAW DB value (empty if null) --}}
  <div
    class="admin__color__picker"
    data-db-theme="{{ strtolower($gSetting->theme_color ?? '') }}"
  >
    @foreach ($allowedColors as $colorItem)
      <button
        type="button"
        class="admin__color__btn button {{ $color === $colorItem ? 'active' : '' }}"
        data-theme="{{ $colorItem }}"
      >
        <span></span>
        {{ $colorItem }}
      </button>
    @endforeach
  </div>
</div>

@script
<script>


document.addEventListener('admin-color-change', function(event) {
  @this.setThemeColor(event?.detail?.themeColor ?? 'default');
});
</script>
@endscript
