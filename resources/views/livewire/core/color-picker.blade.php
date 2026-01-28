<div class="color__picker__container">
  <button
    id="colorPickerbutton{{ $uid }}"
    aria-haspopup="true"
    aria-controls="colorPickerMenu{{ $uid }}"
    class="color__picker__btn button rounded"
  ></button>

  <ul
    id="colorPickerMenu{{ $uid }}"
    role="menu"
    aria-labelledby="colorPickerbutton{{ $uid }}"
    class="color__picker__menu"
  ></ul>
</div>
