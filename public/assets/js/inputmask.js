// npm package: inputmask
// github link: https://github.com/RobinHerbots/Inputmask

(function($) {
  'use strict';

  // initializing inputmask
    $("input[name='dolar']").inputmask('decimal', {
        radixPoint: ".",
        groupSeparator: ".",
        digits: 2,
        autoGroup: true,
        rightAlign: true,
    });
})(jQuery);
