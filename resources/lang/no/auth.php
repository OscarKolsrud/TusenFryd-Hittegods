<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed'   => 'These credentials do not match our records.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    // Activation items
    'sentEmail'        => 'Vi har sendt en e-post til :email.',
    'clickInEmail'     => 'Klikk linken i den for å aktivere kontoen din.',
    'anEmailWasSent'   => 'En epost ble sendt :date til :email.',
    'clickHereResend'  => 'Klikk her for å sende den på nytt.',
    'successActivated' => 'Suksess, kontoen din er blitt aktivert.',
    'unsuccessful'     => 'Kontoen din kunne ikke aktiveres; Vennligst prøv på nytt.',
    'notCreated'       => 'Kontoen din kunne ikke opprettes; Vennligst prøv på nytt.',
    'tooManyEmails'    => 'Det har blitt sendt for mange aktiveringsforespørsler til :email. <br />Vennligst prøv igjen om <span class="label label-danger">:hours timer</span>.',
    'regThanks'        => 'Takk for din registrering, ',
    'invalidToken'     => 'Ugyldig aktiverings kode. ',
    'activationSent'   => 'Aktiverings e-post sendt. ',
    'alreadyActivated' => 'Allerede aktivert. ',

    // Labels
    'whoops'          => 'Oops! ',
    'someProblems'    => 'Der var det noe som gikk galt.',
    'email'           => 'E-Post Adresse',
    'password'        => 'Passord',
    'rememberMe'      => ' Husk Meg',
    'login'           => 'Logg inn',
    'forgot'          => 'Glemt Passord?',
    'forgot_message'  => 'Problemer med å logge inn?',
    'name'            => 'Brukernavn',
    'first_name'      => 'Fornavn',
    'last_name'       => 'Etternavn',
    'confirmPassword' => 'Bekreft Passord',
    'register'        => 'Registrer',

    // Placeholders
    'ph_name'          => 'Brukernavn',
    'ph_email'         => 'E-Post Adresse',
    'ph_firstname'     => 'Fornavn',
    'ph_lastname'      => 'Etternavn',
    'ph_password'      => 'Passord',
    'ph_password_conf' => 'Bekreft Passord',

    // User flash messages
    'sendResetLink' => 'Send nullstillings lenke',
    'resetPassword' => 'Nullstill Passord',
    'loggedIn'      => 'Du er logget inn!',

    // email links
    'pleaseActivate'    => 'Vennligst aktiver kontoen din.',
    'clickHereReset'    => 'Klikk her for å nullstille passordet: ',
    'clickHereActivate' => 'Klikk her for å aktivere kontoen: ',

    // Validators
    'userNameTaken'    => 'Brukernavnet er tatt',
    'userNameRequired' => 'Brukernavn kreves',
    'fNameRequired'    => 'Fornavn kreves',
    'lNameRequired'    => 'Etternavn kreves',
    'emailRequired'    => 'E-Post kreves',
    'emailInvalid'     => 'E-Post er ugyldig',
    'passwordRequired' => 'Passord kreves',
    'PasswordMin'      => 'Passordet må ha minst 6 tegn',
    'PasswordMax'      => 'Passordet må være kortere enn 20 tegn',
    'captchaRequire'   => 'Captcha kreves',
    'CaptchaWrong'     => 'Feil i captcha, vennligst prøv igjen.',
    'roleRequired'     => 'Du mangler tillatelse til dette..',

];
