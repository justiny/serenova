<?php
/*
 * Third party plugins that hijack the theme will call wp_head() to get the header template.
 * We use this to start our output buffer and render into the view/page-plugin.twig template in footer.php
 */
$GLOBALS['timberContext'] = Timber::get_context();
ob_start();

if ( is_front_page() ) { ?>
<style>@import url(https://use.typekit.net/gtk8far.css);html{font-family:sans-serif;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{margin:0}*{outline:none}main,nav,section{display:block}a{background-color:transparent}h1{margin:0}img{border-style:none}svg:not(:root){overflow:hidden}input{font:inherit}input{overflow:visible}input{margin:0}input::-moz-focus-inner{border:0;padding:0}input:-moz-focusring{outline:1px dotted ButtonText}form{margin:0;padding:0;border:0;font-size:100%;vertical-align:baseline}h1,h2,h3,li,p,ul{margin:0;padding:0}html{box-sizing:border-box}*,:after,:before{box-sizing:inherit}img{max-width:100%;height:auto}iframe{border:0}input{-webkit-appearance:none;border-radius:0}@font-face{font-family:akkurat-regular;src:url(../fonts/hinted-Akkurat-Regular.eot);src:local("Akkurat Regular"),local("Akkurat-Regular"),url(../fonts/hinted-Akkurat-Regular.eot?#iefix) format("embedded-opentype"),url(../fonts/hinted-Akkurat-Regular.woff2) format("woff2"),url(../fonts/hinted-Akkurat-Regular.woff) format("woff"),url(../fonts/hinted-Akkurat-Regular.ttf) format("truetype"),url(../fonts/hinted-Akkurat-Regular.svg#Akkurat-Regular) format("svg");font-weight:400;font-style:normal}.text-white{color:#fff}.text-blue{color:#2dc2ec}.text-dark-10{color:#2d2929}.text-dark-20{color:#342c2c}.text-dark-30{color:#463f3f}.text-dark-50{color:#787070}.text-dark-80{color:#dfdfdf}.fill-white{background-color:#fff}.fill-blue{background-color:#2dc2ec}.fill-blue-dark{background-color:#112456}.fill-dark-90{background-color:#fafafa}.button{-webkit-text-size-adjust:100%;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;padding:0;background-color:transparent;background-image:none;font-family:Gotham SSm A,Gotham SSm B,serif;font-style:normal;font-weight:700;font-display:swap;line-height:1;border-radius:0;text-decoration:none;display:inline-block;border:1px solid transparent;text-align:center;width:100%;display:block;text-transform:uppercase;border-radius:3px;z-index:2}@media (min-width:768px){.button{width:auto;display:inline-block}}.button-blue{background-color:#2dc2ec;color:#fff;border:1px solid transparent}.button-red{background-color:#fe4565;color:#fff;border:1px solid transparent}.button-small{font-size:14px;padding:.9375rem 1.25rem;letter-spacing:.93px}.button-medium{padding:1.125rem 1.5625rem}.button-medium{font-size:15px;letter-spacing:1px}.title-1{-webkit-text-size-adjust:100%;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;font-family:caecilia,serif;font-style:normal;font-weight:400;font-display:swap;line-height:1.26;font-size:30px}@media (min-width:576px){.title-1{font-size:calc(4.16667vw + 6px)}}@media (min-width:768px){.title-1{font-size:38px}}.text-20{-webkit-text-size-adjust:100%;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;font-family:akkurat-regular,sans-serif;font-style:normal;line-height:1.4;font-size:18px}@media (min-width:576px){.text-20{font-size:calc(1.04167vw + 12px)}}@media (min-width:768px){.text-20{font-size:20px}}.text-20-bold{-webkit-text-size-adjust:100%;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;font-family:caecilia,serif;font-style:normal;font-weight:700;font-display:swap;line-height:1.2;font-size:18px}@media (min-width:576px){.text-20-bold{font-size:calc(1.04167vw + 12px)}}@media (min-width:768px){.text-20-bold{font-size:20px}}.text-15-bold{font-weight:700}.text-15-bold,.text-15-medium{-webkit-text-size-adjust:100%;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;font-family:Gotham SSm A,Gotham SSm B,serif;font-style:normal;font-display:swap;font-size:15px;line-height:1.2;letter-spacing:.9px}.text-15-medium{font-weight:500}.text-14-medium{-webkit-text-size-adjust:100%;font-weight:500;text-transform:uppercase}.text-14-medium{-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;font-family:Gotham SSm A,Gotham SSm B,serif;font-style:normal;font-display:swap;font-size:14px;line-height:1.2;letter-spacing:.93px}.text-13{font-family:akkurat-regular,sans-serif}.text-13{-webkit-text-size-adjust:100%;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;font-style:normal;font-size:13px;line-height:1.2}.text-13-medium{-webkit-text-size-adjust:100%;-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;font-family:Gotham SSm A,Gotham SSm B,serif;font-style:normal;font-weight:500;font-display:swap;font-size:13px;line-height:1.2;letter-spacing:.93px;text-transform:uppercase}.link-blue{color:#2dc2ec}.link-arrow-blue:after{margin-left:.5rem;content:"";background-image:url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 12'%3E%3Cpath fill='%232DC2EC' fill-rule='evenodd' d='M1.4 0L0 1.4 4.6 6 0 10.6 1.4 12l6-6z'/%3E%3C/svg%3E");background-size:cover;display:inline-block;height:.75rem;width:.4375rem}.caps{text-transform:uppercase}.decoration-none{text-decoration:none}.list-unstyled{margin-left:0;padding-left:0;list-style:none}.xs-mb-10{margin-bottom:10px}.xs-mb-20{margin-bottom:20px}.xs-mb-25{margin-bottom:25px}@media (min-width:768px){.sm-mb-5{margin-bottom:5px}.sm-mb-40{margin-bottom:40px}.sm-mb-150{margin-bottom:150px}}@media (min-width:1025px){.md-mb-0{margin-bottom:0}}@media (min-width:768px){.sm-nmb-50{margin-bottom:-50px}.sm-nmb-120{margin-bottom:-120px}}.xs-mt-15{margin-top:15px}@media (min-width:768px){.sm-mt-5{margin-top:5px}}.xs-mr-5{margin-right:5px}.xs-mr-10{margin-right:10px}.xs-mr-35{margin-right:35px}@media (min-width:1025px){.md-mr-40{margin-right:40px}}.xs-pb-0{padding-bottom:0}.xs-pb-10{padding-bottom:10px}.xs-pb-15{padding-bottom:15px}.xs-pb-20{padding-bottom:20px}.xs-pb-25{padding-bottom:25px}.xs-pb-40{padding-bottom:40px}.xs-pb-60{padding-bottom:60px}@media (min-width:768px){.sm-pb-0{padding-bottom:0}.sm-pb-15{padding-bottom:15px}.sm-pb-20{padding-bottom:20px}.sm-pb-30{padding-bottom:30px}.sm-pb-55{padding-bottom:55px}.sm-pb-65{padding-bottom:65px}.sm-pb-80{padding-bottom:80px}}@media (min-width:1025px){.md-pb-15{padding-bottom:15px}}@media (min-width:1440px){.xl-pb-80{padding-bottom:80px}}.xs-pt-10{padding-top:10px}.xs-pt-15{padding-top:15px}.xs-pt-20{padding-top:20px}.xs-pt-25{padding-top:25px}.xs-pt-40{padding-top:40px}@media (min-width:768px){.sm-pt-0{padding-top:0}.sm-pt-15{padding-top:15px}.sm-pt-20{padding-top:20px}.sm-pt-30{padding-top:30px}.sm-pt-40{padding-top:40px}.sm-pt-65{padding-top:65px}.sm-pt-120{padding-top:120px}}@media (min-width:1025px){.md-pt-45{padding-top:45px}}@media (min-width:1440px){.xl-pt-95{padding-top:95px}}.xs-pl-10{padding-left:10px}.xs-pl-15{padding-left:15px}.xs-pl-20{padding-left:20px}.xs-pl-35{padding-left:35px}@media (min-width:768px){.sm-pl-0{padding-left:0}.sm-pl-10{padding-left:10px}.sm-pl-15{padding-left:15px}.sm-pl-40{padding-left:40px}}@media (min-width:1025px){.md-pl-15{padding-left:15px}.md-pl-30{padding-left:30px}}@media (min-width:1440px){.xl-pl-80{padding-left:80px}}.xs-pr-10{padding-right:10px}.xs-pr-15{padding-right:15px}.xs-pr-20{padding-right:20px}.xs-pr-35{padding-right:35px}@media (min-width:768px){.sm-pr-0{padding-right:0}.sm-pr-10{padding-right:10px}.sm-pr-15{padding-right:15px}.sm-pr-40{padding-right:40px}}@media (min-width:1025px){.md-pr-15{padding-right:15px}.md-pr-30{padding-right:30px}.md-pr-40{padding-right:40px}}@media (min-width:1440px){.xl-pr-80{padding-right:80px}.xl-pr-90{padding-right:90px}}.flex{display:flex}.flex-column{flex-direction:column}.flex-wrap{flex-wrap:wrap}.align-center{align-items:center}.align-baseline{align-items:baseline}.justify-space-between{justify-content:space-between}.justify-center{justify-content:center}.justify-flex-end{justify-content:flex-end}.xs-flex-column{flex-direction:column}@media (min-width:768px){.sm-flex-row{flex-direction:row}}@media (min-width:1025px){.md-flex-row{flex-direction:row}}.absolute{position:absolute}.relative{position:relative}.z4{z-index:400}.header-card-shadow{box-shadow:0 8px 16px 0 rgba(0,0,0,.1)}.es-main{max-width:100rem;overflow-y:hidden;overflow-x:hidden}.es-main{margin-left:auto;margin-right:auto;padding-left:0;padding-right:0;width:100%}.es-grid{padding-left:.625rem;padding-right:.625rem;max-width:75rem;margin-left:.625rem;margin-right:.625rem}@media (min-width:768px){.es-grid{margin-left:auto;margin-right:auto;padding-left:1.25rem;padding-right:1.25rem}}@media (min-width:1025px){.es-grid{margin-left:auto;margin-right:auto;padding-left:1.25rem;padding-right:1.25rem}}.es-row{box-sizing:border-box;min-height:1px;display:flex;flex-grow:0;flex-shrink:1;flex-basis:auto;flex-direction:row;flex-wrap:wrap;margin-left:-.625rem;margin-right:-.625rem}@media (min-width:768px){.es-row{margin-left:-1.25rem;margin-right:-1.25rem}}[class*=es-span-]{position:relative;min-height:1px;padding-left:1px;padding-right:1px;flex-grow:0;flex-shrink:0;flex-basis:auto}@media (min-width:768px){.es-span-sm-4{width:66.6666666667%;max-width:66.6666666667%}}[class*=es-col-]{position:relative;min-height:1px;padding-left:.625rem;padding-right:.625rem;flex-grow:0;flex-shrink:0;flex-basis:auto}@media (min-width:768px){[class*=es-col-]{padding-left:1.25rem;padding-right:1.25rem}}@media (min-width:0){.es-col-xs-6{width:100%;max-width:100%}}@media (min-width:768px){.es-col-sm-2{width:33.3333333333%;max-width:33.3333333333%}}@media (min-width:0){.es-hidden-xs{display:none}}@media (min-width:768px){.es-hidden-sm{display:none}}@media (min-width:1025px){.es-hidden-md{display:none}}@media (min-width:768px){.es-show-sm-flex{display:flex}}@media (min-width:1025px){.es-show-md-flex{display:flex}}@media (min-width:768px){.es-show-sm{display:block}}body,html{overflow-x:hidden;background-color:#fafafa}.es-nav{box-shadow:0 2px 0 0 rgba(0,0,0,.1);position:relative;top:0;left:0;max-width:100rem;margin-left:auto;margin-right:auto;width:100%;z-index:500;overflow-x:visible}.es-nav-desktop-dropdown{height:0;opacity:0;z-index:-1;display:none;top:3.125rem;position:absolute;white-space:nowrap;margin-top:2.1875rem;box-shadow:0 0 15px 0 rgba(0,0,0,.15)}@media (min-width:1025px){.es-nav-desktop-dropdown{margin-top:.625rem}}.es-nav-desktop-dropdown-carat{left:0;top:-.9375rem;padding-left:.9375rem;width:100%;display:block;height:.9375rem;position:absolute}.es-nav-desktop-dropdown-carat:before{content:"";bottom:0;display:block;position:absolute;width:0;height:0;z-index:1000;border:.625rem solid transparent;border-bottom-color:#fff}.es-nav li.has-dropdown{position:relative}.es-nav-spacer{width:8.75rem}.es-nav-top{height:auto}@media (min-width:1025px){.es-nav-top{height:3.125rem}}.es-nav-search{height:0;opacity:0;max-width:100rem;margin:0 auto}.es-nav-search-icon{width:1.25rem;height:auto}@media (min-width:768px){.es-nav-search-icon{width:1.125rem}}.es-nav-search-input{caret-color:#2dc2ec;border:none;background-color:transparent;width:100%}.es-nav-search-input::-webkit-input-placeholder{opacity:.6}.es-nav-search-input::-moz-placeholder{opacity:.6}.es-nav-search-input:-ms-input-placeholder{opacity:.6}.es-nav-search-input::-ms-input-placeholder{opacity:.6}.es-nav-logo{width:9.0625rem}@media (min-width:768px){.es-nav-logo{width:100%;max-width:12.5rem}}.es-nav-icon{width:1rem;height:auto;margin-right:.4375rem}.es-nav-mobile{width:calc(100vw - 10px);display:block;right:0;height:100vh;transform:translateX(100%)}@media (min-width:768px){.es-nav-mobile{display:none}}.es-nav-mobile-menu{display:flex;z-index:1}.es-nav-mobile-menu.-show{display:flex}@media (min-width:768px){.es-nav-mobile-menu.-show{display:none}}.es-nav-mobile-close{display:none;z-index:1}.es-nav-mobile-close.-hide{display:none}.es-nav-mobile-close img{width:.875rem;height:.875rem}.es-nav-mobile-inner{height:calc(100vh - 66px);overflow-y:scroll}.es-nav-mobile-links a{width:50%}.es-nav-mobile-links img{width:1rem;height:.5625rem;transform:rotateX(0deg)}.es-nav-mobile-sub{width:100%;background-color:#0c1c48;border-top:1px solid #d8d8d8;border-bottom:1px solid #d8d8d8;display:none;z-index:-1;height:0;opacity:0}.es-nav-mobile-sub a{width:100%;display:block}.es-nav-mobile-dropdown{width:50%}body:-webkit-full-page-media{background:none!important}.card-callout-wrapper{height:100%;width:100%}.card-callout-content{position:relative;z-index:2}.card-callout-content h3:after{margin-left:.75rem;content:"";background-image:url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 12'%3E%3Cpath fill='%232D2929' fill-rule='evenodd' d='M1.4 0L0 1.4 4.6 6 0 10.6 1.4 12l6-6z'/%3E%3C/svg%3E");background-size:cover;display:inline-block;height:1rem;width:.625rem}@media (max-width:767px){.card-callout-content h3:after{display:none}}.card-callout-bottom-odd:before,.card-callout-top-even:before{content:"";position:absolute;left:-5rem;width:10.5rem;height:10.5rem;border-radius:100%;background-color:#f2f4f5;z-index:1}.card-callout-top-even{box-shadow:0 8px 16px 0 rgba(0,0,0,.1);overflow:hidden;position:relative;width:100%}.card-callout-top-even:before{top:-5rem}.card-callout-bottom-odd{box-shadow:0 8px 16px 0 rgba(0,0,0,.1);overflow:hidden;position:relative;width:100%}.card-callout-bottom-odd:before{bottom:-5rem}.es-image-header{background-position:0 0;background-repeat:no-repeat;background-size:cover}@media (min-width:768px){.es-image-header{background-position:100% 0}}.es-image-header-card{width:100%}@media (min-width:768px){.es-image-header-card{background-image:url(../images/header-mask@2x.png);background-position:0 0;background-repeat:no-repeat;background-size:35% 100%;width:80%}}@media (min-width:1025px){.es-image-header-card{width:65%}}.es-image-header-card-inner .button{margin-bottom:20px}@media (min-width:768px){.es-image-header-card-inner{margin-left:30%}}@media (min-width:1440px){.es-image-header-card-inner{margin-left:35%}}@media (min-width:1148px){.es-image-header-card-inner .button{margin-bottom:0}}.es-image-header-card-actions{text-align:center}@media (min-width:768px){.es-image-header-card-actions{text-align:left}}</style>
<?php }
