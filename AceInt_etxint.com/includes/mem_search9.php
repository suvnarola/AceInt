<!doctype html>
<html>
  <head>
    <title>Test</title>
    <style type="text/css">
      body { margin: 0; padding: 0; }
      #header {
        padding: 5px 10px;
        background-color: #627AAD;
        font-family: "lucida grande", tahoma;
        font-size: 11px;
      }
      div.searchBox {
        border: 1px solid #899BC1;
        border-top-color: #6484B4;
        background-color: #fff;
        width: 348px;
        height: 22px;
      }
      div.searchBox span.searchContainer {
        border: 0;
        border-top: 1px solid #EDEDED;
        padding: 1px 0 2px;
        position: relative;
      }
      div.searchBox input {
        width: 315px;
        color: #444;
        border: 0;
        border-right: 1px solid #E5E5E5;
        outline: none;
        padding: 1px 5px 2px 0;
        margin: 0 22px 0 5px;
        font-family: "lucida grande", tahoma;
        font-size: 11px;
        height: 16px;
      }
      div.searchBox button {
        background: #fff url(https://s-static.ak.facebook.com/rsrc.php/v1/z_/r/2Oin6nHA4Mx.png) no-repeat 0 0;
        border: 0;
        cursor: pointer;
        display: block;
        height: 19px;
        padding: 0;
        position: absolute;
        right: 0;
        top: 0;
        width: 22px;
      }
      div.searchBox ul {
        margin: 0;
        padding: 0;
        position: absolute;
        top: 28px;
        background-color: #fff;
        border: 1px solid #333;
        border-bottom: 2px solid #293E6A;
        width: 346px;
        list-style: none;
      }
      div.searchBox ul li.header {
        background-color: #f2f2f2;
        min-height: 10px;
        padding: 2px 15px 2px 6px;
        border-bottom: 0;
      }
      div.searchBox ul li.header span {
        font-weight: bold;
        padding-bottom: 2px;
        display: block;
        cursor: pointer;
      }
      div.searchBox ul li.person,
      div.searchBox ul li.place {
        min-height: 50px;
        padding: 2px 30px 2px 63px;
        border: solid #fff;
        position: relative;
        border-width: 1px 0;
      }
      div.searchBox ul li.person a,
      div.searchBox ul li.place a {
        outline: none;
        cursor: pointer;
        color: #3B5998;
        text-decoration: none;
      }
      div.searchBox ul li.person a img,
      div.searchBox ul li.place a img {
        background-color: #ECEFF5;
        display: block;
        height: 50px;
        left: 6px;
        position: absolute;
        width: 50px;
        border: 0;
      }
      div.searchBox ul li.person a span.text,
      div.searchBox ul li.place a span.text {
        display: block;
        font-weight: bold;
        padding-bottom: 2px;
      }
    </style>
  </head>
  <body>
    <div id="header">
      <div id="search">
        <div class="searchBox">
          <span class="searchContainer"><input data-bind="value: query, valueUpdate: 'afterkeyup', event: { keyup: doSearch }" /><button data-bind="click: doSearch"></button></span>
          <ul class="results" data-bind='template: { name: "resultItem", foreach: results }, visible: results().length > 0'></ul>
        </div>

        <script type="text/html" id="resultItem">
          <li class="${ type() }">
            {{if type() == "header"}}
              <span data-bind="text: text"></span>
            {{else}}
              <a href="#">
                <img src="${ imageUrl() }" />
                <span class="text" data-bind="text: name"></span>
              </a>
            {{/if}}
          </li>
        </script>
      </div>
    </div>

    <script type="text/javascript" src="jquery-1.6.min.js"></script>
    <script type="text/javascript" src="jquery.tmpl.js"></script>
    <script type="text/javascript" src="knockout-1.2.0.js"></script>
    <script type="text/javascript" src="knockout-mapping.js"></script>
    <script type="text/javascript">
      (function($)
      {
        var baseModel =
        {
          query: "",
          results: []
        };

        var viewModel = ko.mapping.fromJS(baseModel);
        viewModel.doSearch = function()
        {
          var $this = this;
          setTimeout(function()
          {
            var resultModel = null;
            var q = $this.query();
            if (q == "")
            {
              resultModel = { results: [] };
              ko.mapping.updateFromJS(viewModel, resultModel);
            }
            else
            {
              $.ajax({
                url: "http://fidelitydesign.net/pub/json.asp",
                data: { "query": q },
                type: "GET",
                dataType: "json",
                success: function(r)
                {
                  resultModel = r;
                  ko.mapping.updateFromJS(viewModel, resultModel);
                }
              });
            }
          }, 1);

          return true;
        };

        window.vm = viewModel;

        ko.applyBindings(viewModel, $("#search").get(0));
      })(jQuery);
    </script>
  </body>
</html>