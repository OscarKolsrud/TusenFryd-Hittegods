<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Emails Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for various emails that
    | we need to display to the user. You are free to modify these
    | language lines according to your application's requirements.
    |
    */

    // Activate new user account email.
    'activationSubject'  => 'Konto aktivering',
    'activationGreeting' => 'Velkommen!',
    'activationMessage'  => 'Du må aktivere kontoen din før du kan bruke alle tjenestene som tilbys.',
    'activationButton'   => 'Aktiver',
    'activationThanks'   => 'Takk for at du aktiverer!',

    // Goobye email.
    'goodbyeSubject'  => 'Trist å se deg forlate oss...',
    'goodbyeGreeting' => 'Hei :username,',
    'goodbyeMessage'  => 'Trist at du velger å forlate oss, dette er bare en kjapp beskjed at kontoen din nå er deaktivert og vil bli slettet om '.config('settings.restoreUserCutoff').' dager. Frem til denne tiden er utløpet kan du fortsatt gjenopprette kontoen.',
    'goodbyeButton'   => 'Gjenopprett konto',
    'goodbyeThanks'   => 'Vi håper å se deg igjen!',

];
