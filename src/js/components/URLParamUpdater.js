const _ = require('lodash');
const queryString = require("query-string");

$(document).ready(function () {
  var oldParams = queryString.parse(location.search);
  var referrer = document.referrer.split('?')[1] || '';
  var referrerParams;
  var params = {};

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


  $("a").each(function (i, link) {
    var base = link.href.split("?")[0];
    var href = link.href;

    if (href.includes('serenova.com')) {
      // if (params.CLS = "MKTG_Website_Inquiry") {
      //   params.CLS = ""
      // }

      if (!$(link).hasClass("pagination-link") || !$(link).hasClass("page-number")) {
        params.s = undefined;
      } else {
        params.s = oldParams.s;
      }


      if (!params.CLS) {
        if (window.location.href.includes("/blog") && $(link).parents('.es-main').length) {
          params.CLS = "MKTG_Blog";
          const stringified = queryString.stringify(params);
          if (stringified.length) {
            link.href = base + "?" + stringified;
          }
        } else if (href.includes("/become-a-partner") || href.includes("/contact") || href.includes("/request-a-demo") || href.includes("/premium-support") || href.includes("/trial") || href.includes("/request-partner-portal-access") || href.includes("/unsubscribe")) {
          params.CLS = "MKTG_Website_Inquiry";
          const stringified = queryString.stringify(params);
          if (stringified.length) {
            link.href = base + "?" + stringified;
          }
        } else if (href.includes("//succeed.serenova.com") || href.includes("//success.serenova.com") )  {
          params.CLS = "MKTG_Website_Download";
          console.log('updating on: ', href);
          const stringified = queryString.stringify(params);
          if (stringified.length) {
            link.href = base + "?" + stringified;
          }
        } else if ( window.location.href.includes("//www.serenova.com/resources") && href.includes("//www.serenova.com/resources/wp-content/uploads/") ) {
          params.CLS = "MKTG_Website_Download";
          console.log('updating on: ', href);
          const stringified = queryString.stringify(params);
          if (stringified.length) {
            link.href = base + "?" + stringified;
          }
        }
      } else {
        const stringified = queryString.stringify(params);
        link.href = base + "?" + stringified;
      }
    }
  });

  $("iframe").each(function (i, iframe) {
    var base = iframe.src.split("?")[0];
    var src = iframe.src;

    if (!location.search.length || (!location.search.length && !params.CLS)) {
      if (src.includes("success.serenova.com")) {
        params.CLS = "MKTG_Website_Inquiry";
        const stringified = queryString.stringify(params);
        if (stringified.length) {
          iframe.href = base + "?" + stringified;
        }
      }
    } else {
      const stringified = queryString.stringify(params);
      if (stringified.length) {
        iframe.href = base + "?" + stringified;
      }
    }
  });
});


// links in blog post get blog thing
// links that contain an iframe get marketing website inquiry
// links that point to success
