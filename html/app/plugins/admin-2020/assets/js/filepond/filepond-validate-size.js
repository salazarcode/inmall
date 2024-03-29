/*!
 * FilePondPluginFileValidateSize 2.2.1
 * Licensed under MIT, https://opensource.org/licenses/MIT/
 * Please visit https://pqina.nl/filepond/ for details.
 */

/* eslint-disable */

!(function (e, i) {
  "object" == typeof exports && "undefined" != typeof module
    ? (module.exports = i())
    : "function" == typeof define && define.amd
    ? define(i)
    : ((e = e || self).FilePondPluginFileValidateSize = i());
})(this, function () {
  "use strict";
  var e = function (e) {
    var i = e.addFilter,
      l = e.utils,
      E = l.Type,
      n = l.replaceInString,
      _ = l.toNaturalFileSize;
    return (
      i("ALLOW_HOPPER_ITEM", function (e, i) {
        var l = i.query;
        if (!l("GET_ALLOW_FILE_SIZE_VALIDATION")) return !0;
        var E = l("GET_MAX_FILE_SIZE");
        if (null !== E && e.size >= E) return !1;
        var n = l("GET_MIN_FILE_SIZE");
        return !(null !== n && e.size <= n);
      }),
      i("LOAD_FILE", function (e, i) {
        var l = i.query;
        return new Promise(function (i, E) {
          if (!l("GET_ALLOW_FILE_SIZE_VALIDATION")) return i(e);
          var t = l("GET_FILE_VALIDATE_SIZE_FILTER");
          if (t && !t(e)) return i(e);
          var a = l("GET_MAX_FILE_SIZE");
          if (null !== a && e.size >= a)
            E({
              status: {
                main: l("GET_LABEL_MAX_FILE_SIZE_EXCEEDED"),
                sub: n(l("GET_LABEL_MAX_FILE_SIZE"), { filesize: _(a) }),
              },
            });
          else {
            var I = l("GET_MIN_FILE_SIZE");
            if (null !== I && e.size <= I)
              E({
                status: {
                  main: l("GET_LABEL_MIN_FILE_SIZE_EXCEEDED"),
                  sub: n(l("GET_LABEL_MIN_FILE_SIZE"), { filesize: _(I) }),
                },
              });
            else {
              var u = l("GET_MAX_TOTAL_FILE_SIZE");
              if (null !== u)
                if (
                  l("GET_ACTIVE_ITEMS").reduce(function (e, i) {
                    return e + i.fileSize;
                  }, 0) > u
                )
                  return void E({
                    status: {
                      main: l("GET_LABEL_MAX_TOTAL_FILE_SIZE_EXCEEDED"),
                      sub: n(l("GET_LABEL_MAX_TOTAL_FILE_SIZE"), {
                        filesize: _(u),
                      }),
                    },
                  });
              i(e);
            }
          }
        });
      }),
      {
        options: {
          allowFileSizeValidation: [!0, E.BOOLEAN],
          maxFileSize: [null, E.INT],
          minFileSize: [null, E.INT],
          maxTotalFileSize: [null, E.INT],
          fileValidateSizeFilter: [null, E.FUNCTION],
          labelMinFileSizeExceeded: ["File is too small", E.STRING],
          labelMinFileSize: ["Minimum file size is {filesize}", E.STRING],
          labelMaxFileSizeExceeded: ["File is too large", E.STRING],
          labelMaxFileSize: ["Maximum file size is {filesize}", E.STRING],
          labelMaxTotalFileSizeExceeded: [
            "Maximum total size exceeded",
            E.STRING,
          ],
          labelMaxTotalFileSize: [
            "Maximum total file size is {filesize}",
            E.STRING,
          ],
        },
      }
    );
  };
  return (
    "undefined" != typeof window &&
      void 0 !== window.document &&
      document.dispatchEvent(
        new CustomEvent("FilePond:pluginloaded", { detail: e })
      ),
    e
  );
});
