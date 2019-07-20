$(document).ready(function() {
  var params = window.location.toString().split("?")[1];

  if (!!params) {
    $('.es-form-embed-body iframe').each(function (i, embed) {
      var newSrc = embed.src;

      if (newSrc.includes("?")) {
        newSrc = newSrc + '&' + params;
      } else {
        newSrc = newSrc + '?' + params;
      }

      var newIframe = document.createElement('iframe');
      newIframe.setAttribute('src', newSrc);
      newIframe.setAttribute('width', '100%');
      newIframe.setAttribute('type', 'text/html');
      newIframe.setAttribute('scrolling', 'no');
      newIframe.setAttribute('frameborder', 0);
      newIframe.setAttribute('allowTransparency', 'true');
      newIframe.style.border = '0';

      embed.replaceWith(newIframe);
    });
  }

  if (typeof iFrameResize != "undefined") {
    iFrameResize(null, ".es-form-embed-body iframe");
  }
});
