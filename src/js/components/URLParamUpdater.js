const _ = require('lodash');
const queryString = require("query-string");

$(document).ready(function () {
  var oldParams = queryString.parse(location.search);
  var referrer = document.referrer.split('?')[1] || '';
  var referrerParams;
  var params = {};

  // Set object for the params we're tracking to pass through
  var trackedParams = [
    "utm_content",
    "utm_term",
    "utm_campaign",
    "utm_source",
    "utm_medium",
    "CLS"
  ];

  if (referrer.length) {
    referrerParams = queryString.parse(referrer);
  }

  // Set current params either to the params from the referrer
  // or if there are params from the previous page
  trackedParams.forEach(function(param) {
    if (!!referrerParams && !!referrerParams[param]) {
      params[param] = referrerParams[param];
    } else if (!!oldParams[param]) {
      params[param] = oldParams[param];
    } else {
      params[param] = undefined;
    }
  });

  params = _.merge(oldParams, params);

  // loop over each link on page to add params
  $("a").each(function (i, link) {
    var base = link.href.split("?")[0];
    var href = link.href;
    var linkParams = params;

    // make sure we're on serenova
    if (href.includes('serenova.com')) {

      // don't mess with pagination
      if (!$(link).hasClass("pagination-link") || !$(link).hasClass("page-number")) {
        linkParams.s = undefined;
      } else {
        linkParams.s = oldParams.s;
      }

      // only loop through if we don't have any params
      if (!linkParams.CLS) {
        if (window.location.href.includes("/blog") && $(link).parents('.es-main').length) {
          linkParams.CLS = "MKTG_Blog";
          const stringified = queryString.stringify(linkParams);
          if (stringified.length) {
            link.href = base + "?" + stringified;
          }
        } else if (window.location.href.includes("/become-a-partner") || window.location.href.includes("/contact") || window.location.href.includes("/request-a-demo") || window.location.href.includes("/premium-support") || window.location.href.includes("/trial") || window.location.href.includes("/request-partner-portal-access") || window.location.href.includes("/unsubscribe")) {
          linkParams.CLS = "MKTG_Website_Inquiry";
          const stringified = queryString.stringify(linkParams);
          if (stringified.length) {
            link.href = base + "?" + stringified;
          }
        } else if ( window.location.href.includes("/resources") && ( href.includes("succeed.serenova.com") || href.includes("success.serenova.com") || href.includes("/wp-content/uploads/") ) )  {
          linkParams.CLS = "MKTG_Website_Download";
          const stringified = queryString.stringify(linkParams);
          if (stringified.length) {
            link.href = base + "?" + stringified;
          }
        }
      } else {
        const stringified = queryString.stringify(linkParams);
        link.href = base + "?" + stringified;
      }
    }
  });

  $("iframe").each(function (i, iframe) {
    var base = iframe.src.split("?")[0];
    var src = iframe.src;
    var iframeParams = params;

    if (!location.search.length || (!location.search.length && !iframeParams.CLS)) {
      if (src.includes("success.serenova.com")) {
        iframeParams.CLS = "MKTG_Website_Inquiry";
        const stringified = queryString.stringify(iframeParams);
        if (stringified.length) {
          iframe.href = base + "?" + stringified;
        }
      }
    } else {
      const stringified = queryString.stringify(iframeParams);
      if (stringified.length) {
        iframe.href = base + "?" + stringified;
      }
    }
  });
});


// links in blog post get blog thing
// links that contain an iframe get marketing website inquiry
// links that point to success
