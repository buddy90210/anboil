(function($){

  // add togglers to h1
  function transformHeading(){
    const h1 = document.querySelector('h1');
    if (h1){
      h1.innerHTML = 'Рассчитать стоимость <br /><span class="calctoggler" data-calc="hran">хранения</span> / <span class="calctoggler" data-calc="pereval">перевалки</span>';
    }
  }

  // changes visibility of fields, and store calctype to hidden input
  function addTogglerActions(){
    const togglers = document.querySelectorAll('h1 .calctoggler');
    if (togglers.length){
      togglers.forEach(function(el, ind){
        el.addEventListener('click', function(e){

          // check if double-clicked
          const activated = document.querySelector('.calctoggler.active');
          if (activated == this){
            return false;
          }

          // check if calctype field is exist
          const calcfield = document.querySelector('input[name="calctype"]');
          if (!calcfield) return false; // exit if field doesn't exist

          // change calcfield value and body data-attr (for hiding excess time-field)
          calcfield.value = this.getAttribute('data-calc') ?? '';
          document.body.setAttribute('data-calc', calcfield.value);

          // remove active-class from previous toggler
          if (activated) activated.classList.remove('active');
          this.classList.add('active');

          // change visibility of time-field. Also, set value to required-field "time"
          const timeField = document.querySelector('.service__calculator input[name="time"]');
          const form = timeField.closest('form');
          if ( (this.getAttribute('data-calc') ?? '') === 'pereval'){
            timeField.value = "1";
            form.querySelector('input[type="submit"]').classList.add('programmatically-filled-field');
          } else {
            timeField.value = "";
            form.querySelector('input[type="submit"]').classList.remove('programmatically-filled-field');
          }

          // hide result-field
          const resultArea = document.querySelector('.service .service__actions');
          if (resultArea){
            resultArea.classList.add('visually-hidden');
          }

        });
      });
    }
  }

  // first toggler activating
  function initFirstTogglerAfterLoading(){
    $("h1 .calctoggler").first().click();
  }

  // starting
  if (document.querySelector('.service form.services-storage-form')){
    transformHeading();
    addTogglerActions();
    initFirstTogglerAfterLoading();
  }

}(jQuery))
