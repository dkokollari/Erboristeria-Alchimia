function CustomValidation() {
  this.invalidities = [];
  this.validityChecks = [];
}

CustomValidation.prototype = {
  addInvalidity: function(message) {
    this.invalidities.push(message);
  },
  getInvalidities: function() {
    return this.invalidities.join('. \n');
  },
  checkValidity: function(input) {
    for (var i = 0; i < this.validityChecks.length; i++) {
      var isInvalid = this.validityChecks[i].isInvalid(input);
      if (isInvalid) {
        this.addInvalidity(this.validityChecks[i].invalidityMessage);
        this.validityChecks[i].element.classList.add('invalid');
        this.validityChecks[i].element.classList.remove('valid');
      } else {
        this.validityChecks[i].element.classList.remove('invalid');
        this.validityChecks[i].element.classList.add('valid');
      }

      var requirementElement = this.validityChecks[i].element;
      if (requirementElement) {
        if (isInvalid) {
          requirementElement.classList.add('invalid');
          requirementElement.classList.remove('valid');
        } else {
          requirementElement.classList.remove('invalid');
          requirementElement.classList.add('valid');
        }
      } // end if requirementElement
    }
  }
};

/* checks e messaggi d'errore */

var nomeValidityChecks = [{
  isInvalid: function(input) {
    if (input.value === "") return false;
    else return input.value.length < 3;
  },
  invalidityMessage: 'Il nome deve essere lungo almeno 3 caratteri',
  element: document.querySelector('div[class="form_field nome"] .input-requirements li:nth-child(1)')
}, {
  isInvalid: function(input) {
    var illegalCharacters = input.value.match(/[^a-zA-Z]/g);
    if (input.value === "") return false;
    else return (illegalCharacters ? true : false);
  },
  invalidityMessage: 'Sono ammesse solo lettere',
  element: document.querySelector('div[class="form_field nome"] .input-requirements li:nth-child(2)')
}];

var cognomeValidityChecks = [{
  isInvalid: function(input) {
    if (input.value === "") return false;
    else return input.value.length < 3;
  },
  invalidityMessage: 'Il cognome deve essere lungo almeno 3 caratteri',
  element: document.querySelector('div[class="form_field cognome"]  .input-requirements li:nth-child(1)')
}, {
  isInvalid: function(input) {
    var illegalCharacters = input.value.match(/[^a-zA-Z]/g);
    if (input.value === "") return false;
    else return (illegalCharacters ? true : false);
  },
  invalidityMessage: 'Sono ammesse solo lettere',
  element: document.querySelector('div[class="form_field cognome"] .input-requirements li:nth-child(2)')
}];

var emailValidityChecks = [{
  isInvalid: function(input) {
    return !input.value.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/);
  },
  invalidityMessage: 'Questa e-mail sembra non essere valida',
  element: document.querySelector('div[class="form_field email"]')
}];

var passwordValidityChecks = [{
  isInvalid: function(input) {
    return input.value.length < 8 | input.value.length > 100;
  },
  invalidityMessage: 'La password deve essere tra 8 e 100 caratteri',
  element: document.querySelector('div[class="form_field password"] .input-requirements li:nth-child(1)')
}, {
  isInvalid: function(input) {
    return !input.value.match(/[0-9]/g);
  },
  invalidityMessage: 'Almeno 1 numero richiesto',
  element: document.querySelector('div[class="form_field password"] .input-requirements li:nth-child(2)')
}, {
  isInvalid: function(input) {
    return !input.value.match(/[a-z]/g);
  },
  invalidityMessage: 'Almeno 1 lettera minuscola richiesta',
  element: document.querySelector('div[class="form_field password"] .input-requirements li:nth-child(3)')
}, {
  isInvalid: function(input) {
    return !input.value.match(/[A-Z]/g);
  },
  invalidityMessage: 'Almeno 1 lettera maiuscola richiesta',
  element: document.querySelector('div[class="form_field password"] .input-requirements li:nth-child(4)')
}];

var passwordConfermaValidityChecks = [{
  isInvalid: function() {
    return passwordConfermaInput.value != passwordInput.value;
  },
  invalidityMessage: 'Questa password deve coincidere con la prima',
  element: document.querySelector('div[class="form_field password_conferma"]')
}];

var dataNascitaValidityChecks = [{
  isInvalid: function(input) {
    return input.value === "";
  },
  invalidityMessage: 'La data non deve essere vuota',
  element: document.querySelector('div[class="form_field_static data_nascita"]')
}, {
  isInvalid: function(input) {
    var date_input = new Date(String(input.value));
    var today = new Date();
    return date_input >= today;
  },
  invalidityMessage: 'La data non risulta valida',
  element: document.querySelector('div[class="form_field_static data_nascita"]')
}];


/* controllo input e impostazione messaggi d'errore */

function checkInput(input) {
  input.CustomValidation.invalidities = [];
  input.CustomValidation.checkValidity(input);

  if (input.CustomValidation.invalidities.length === 0 && input.value !== '') {
    input.setCustomValidity('');
  } else {
    var message = input.CustomValidation.getInvalidities();
    input.setCustomValidity(message);
  }
}

/* ottenimento elementi */

var nomeInput = document.getElementById('nome');
var cognomeInput = document.getElementById('cognome');
var emailInput = document.getElementById('email');
var passwordInput = document.getElementById('password');
var passwordConfermaInput = document.getElementById('password_conferma');
var dataNascitaInput = document.getElementById('data_nascita');

/* chiamate ai checks */

nomeInput.CustomValidation = new CustomValidation();
nomeInput.CustomValidation.validityChecks = nomeValidityChecks;

cognomeInput.CustomValidation = new CustomValidation();
cognomeInput.CustomValidation.validityChecks = cognomeValidityChecks;

emailInput.CustomValidation = new CustomValidation();
emailInput.CustomValidation.validityChecks = emailValidityChecks;

passwordInput.CustomValidation = new CustomValidation();
passwordInput.CustomValidation.validityChecks = passwordValidityChecks;

passwordConfermaInput.CustomValidation = new CustomValidation();
passwordConfermaInput.CustomValidation.validityChecks = passwordConfermaValidityChecks;

dataNascitaInput.CustomValidation = new CustomValidation();
dataNascitaInput.CustomValidation.validityChecks = dataNascitaValidityChecks;

/* aggiunta eventListener ai campi input */

var inputs = document.querySelectorAll('input:not([type="submit"])');
for (var i = 0; i < inputs.length; i++) {
  inputs[i].addEventListener('keyup', function() {
    checkInput(this);
  });
  inputs[i].addEventListener('click', function() {
    checkInput(this);
  });
}

var submit = document.querySelector('input[type="submit"]');
submit.addEventListener('click', function() {
  for (var i = 0; i < inputs.length; i++) {
    checkInput(inputs[i]);
  }
});
