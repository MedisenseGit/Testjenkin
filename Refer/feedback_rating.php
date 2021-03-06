<!doctype html>
<html class="no-js" lang="">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>jQuery Bar Rating - Minimal, light-weight jQuery ratings</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=1024, initial-scale=1">

    <link rel="stylesheet" href="starrating/css/normalize.min.css">
    <link rel="stylesheet" href="starrating/css/main.css">
    <link rel="stylesheet" href="starrating/css/examples.css">

    <!-- Icons -->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <style>
      @font-face {
        font-family: 'Glyphicons Halflings';
        src:url('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/fonts/glyphicons-halflings-regular.eot');
        src:url('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/fonts/glyphicons-halflings-regular.eot?#iefix') format('embedded-opentype'),
          url('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/fonts/glyphicons-halflings-regular.woff') format('woff'),
          url('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/fonts/glyphicons-halflings-regular.ttf') format('truetype'),
          url('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/fonts/glyphicons-halflings-regular.svg#glyphicons-halflingsregular') format('svg');
      }
    </style>

    <!-- Themes -->
    <link rel="stylesheet" href="starrating/dist/themes/bars-1to10.css">
    <link rel="stylesheet" href="starrating/dist/themes/bars-movie.css">
    <link rel="stylesheet" href="starrating/dist/themes/bars-square.css">
    <link rel="stylesheet" href="starrating/dist/themes/bars-pill.css">
    <link rel="stylesheet" href="starrating/dist/themes/bars-reversed.css">
    <link rel="stylesheet" href="starrating/dist/themes/bars-horizontal.css">

    <link rel="stylesheet" href="starrating/dist/themes/fontawesome-stars.css">
    <link rel="stylesheet" href="starrating/dist/themes/css-stars.css">
    <link rel="stylesheet" href="starrating/dist/themes/bootstrap-stars.css">
    <link rel="stylesheet" href="starrating/dist/themes/fontawesome-stars-o.css">

    <!-- Fonts -->
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Source+Code+Pro" rel="stylesheet" type="text/css">

  </head>
  <body>
   

    <section class="section section-examples">
      
      <div class="examples">
        
        <div class="row">
          <div class="col col-fullwidth">
            <div class="star-ratings start-ratings-main clearfix">
              <h1>How about star ratings?</h1>
              <p>The plugin comes with a few flavours of star ratings compatible with popular libraries.</p>
              <div class="stars stars-example-fontawesome">
                <select id="example-fontawesome" name="rating" autocomplete="off">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
                <span class="title">Font Awesome</span>
              </div>
              <div class="stars stars-example-css">
                <select id="example-css" name="rating" autocomplete="off">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
                <span class="title">CSS Stars</span>
              </div>
              <div class="stars stars-example-bootstrap">
                <select id="example-bootstrap" name="rating" autocomplete="off">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                </select>
                <span class="title">Bootstrap</span>
              </div>
            </div>
          </div>
          <div class="col col-fullwidth">
            <div class="star-ratings">
              <p>It can be used to display fractional star ratings.</p>
              <div class="stars stars-example-fontawesome-o">
                <select id="example-fontawesome-o" name="rating" data-current-rating="5.6" autocomplete="off">
                  <option value=""></option>
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                </select>
                <span class="title current-rating">
                  Current rating: <span class="value"></span>
                </span>
                <span class="title your-rating hidden">
                  Your rating: <span class="value"></span>&nbsp;
                  <a href="#" class="clear-rating"><i class="fa fa-times-circle"></i></a>
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section section-docs">
      <div class="docs">
        <div class="how-to-use">
          <header>How to use</header>
          <div class="instructions">
            <ol>
              <li>
                <p>
                  Get the plugin from <a href="http://github.com/antennaio/jquery-bar-rating">GitHub</a>
                  or install with Bower or NPM:
                </p>

<pre>
<code>
bower install jquery-bar-rating
</code>
</pre>

<pre>
<code>
npm install jquery-bar-rating
</code>
</pre>

              </li>
              <li>
                <p>Create a select field:</p>

<pre>
<code>
&lt;select id=&quot;example&quot;&gt;
  &lt;option value=&quot;1&quot;&gt;1&lt;/option&gt;
  &lt;option value=&quot;2&quot;&gt;2&lt;/option&gt;
  &lt;option value=&quot;3&quot;&gt;3&lt;/option&gt;
  &lt;option value=&quot;4&quot;&gt;4&lt;/option&gt;
  &lt;option value=&quot;5&quot;&gt;5&lt;/option&gt;
&lt;/select&gt;
</code>
</pre>

              </li>
              <li>
                <p>
                  If you would like to use one of the provided themes include the theme
                  in the head section of the page. Adjust the path to the CSS file
                  and make sure it points to the correct theme file. In this example we are
                  also pulling Font Awesome icons from a CDN. 
                </p>

<pre>
<code>
&lt;link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css"&gt;
&lt;link rel="stylesheet" href="fontawesome-stars.css"&gt;
</code>
</pre>

              </li>
              <li>
                <p>
                  Include and call the plugin (after jQuery v1.7.2+)
                </p>

<pre>
<code>
&lt;script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"&gt;&lt;/script&gt;
&lt;script src="jquery.barrating.min.js"&gt;&lt;/script&gt;
&lt;script type="text/javascript"&gt;
   $(function() {
      $('#example').barrating({
        theme: 'fontawesome-stars'
      });
   });
&lt;/script&gt;
</code>
</pre>

              </li>
              <li>
                You are done.
              </li>
            </ol>
          </div>
        </div>

        <div class="configuration" id="nav-configuration">
          <header>Configuration</header>
          <div class="instructions">
            <p>
              <strong>theme: ''</strong><br>
              Defines a theme.
            </p>
            <p>
              <strong>initialRating: null</strong><br>
              Defines initial rating.
            </p>
            <p>
              The default value is `null`, which means that the plugin will try to set the initial rating by finding an option with a `selected` attribute.
            </p>
            <p>
              Optionally, if your ratings are numeric, you can pass a fractional rating here (2.5, 2.8, 5.5). Currently the only theme that supports displaying of fractional ratings is the `fontawesome-stars-o` theme.
            </p>
            <p>
              <strong>allowEmpty: null</strong><br>
              If set to true, users will be able to submit empty ratings.
            </p>
            <p>
              The default value is `null`, which means that empty ratings will be allowed under the condition that the select field already contains a first option with an empty value.
            </p>
            <p>
              <strong>emptyValue: ''</strong><br>
              Defines a value that will be considered empty. It is unlikely you will need to modify this setting.
            </p>
            <p>
              <strong>showValues: false</strong><br>
              If set to true, rating values will be displayed on the bars.
            </p>
            <p>
              <strong>showSelectedRating: true</strong><br>
              If set to true, user selected rating will be displayed next to the widget.
            </p>
            <p>
              <strong>deselectable: true</strong><br>
              If set to true, users will be able to deselect ratings.
            </p>
            <p>
              For this feature to work the `allowEmpty` setting needs to be set to `true` or the select field must contain a first option with an empty value.
            </p>
            <p>
              <strong>reverse: false</strong><br>
              If set to true, the ratings will be reversed.
            </p>
            <p>
              <strong>readonly: false</strong><br>
              If set to true, the ratings will be read-only.
            </p>
            <p>
              <strong>fastClicks: true</strong><br>
              Remove 300ms click delay on touch devices.
            </p>
            <p>
              <strong>hoverState: true</strong><br>
              Change state on hover.
            </p>
            <p>
              <strong>silent: false</strong><br>
              Supress callbacks when controlling ratings programatically.
            </p>
            <p>
              <strong>triggerChange: true</strong><br>
              Trigger change event on the select field when ratings are set or reset.
            </p>
          </div>
        </div>

        <div class="methods" id="nav-methods">
          <header>Methods</header>
          <div class="instructions">
            <p>
              <strong>$('select').barrating('show');</strong><br>
              Shows the rating widget.
            </p>
            <p>
              <strong>$('select').barrating('set', value);</strong><br>
              Sets the value of the rating widget.<br>
              The value needs to exist in the underlying select field.
            </p>
            <p>
              <strong>$('select').barrating('readonly', state);</strong><br>
              Switches the read-only state to true or false.<br>
            </p>
            <p>
              <strong>$('select').barrating('clear');</strong><br>
              Clears the rating.
            </p>
            <p>
              <strong>$('select').barrating('destroy');</strong><br>
              Destroys the rating widget.
            </p>
          </div>
        </div>

        <div class="callbacks" id="nav-callbacks">
          <header>Callbacks</header>
          <div class="instructions">
            <p>
              <strong>onSelect:function(value, text, event)</strong><br>
              Fired when a rating is selected.<br>
              If the rating was set programmatically by using the `set` method event will be null.
            </p>
            <p>
              <strong>onClear:function(value, text)</strong><br>
              Fired when a rating is cleared.
            </p>
            <p>
              <strong>onDestroy:function(value, text)</strong><br>
              Fired when a rating is destroyed.
            </p>
          </div>
        </div>

        <div class="faq" id="nav-faq">
          <header>FAQ</header>
          <div class="instructions">
            <div  class="question">
              <p>
                <strong>How does this plugin work?</strong>
              </p>
              <p>
                When you initialise the plugin the select field will be wrapped in a div
                with a `.br-theme-fontawesome-stars` class (or a different class indicating
                the theme currently in use). The select field will get hidden and a rating widget
                will be appended right after the select field.
              </p>
              <p>Expected result:</p>

<pre>
<code>
&lt;div class=&quot;br-wrapper br-theme-fontawesome-stars&quot;&gt;
  &lt;select id=&quot;example&quot;&gt; &lt;!-- now hidden --&gt;
    &lt;option value=&quot;1&quot;&gt;1&lt;/option&gt;
    &lt;option value=&quot;2&quot;&gt;2&lt;/option&gt;
    &lt;option value=&quot;3&quot;&gt;3&lt;/option&gt;
    &lt;option value=&quot;4&quot;&gt;4&lt;/option&gt;
    &lt;option value=&quot;5&quot;&gt;5&lt;/option&gt;
  &lt;/select&gt;
  ...rating widget...
&lt;/div&gt;
</code>
</pre>

            </div>
            <div class="question">
              <p>
                <strong>How do I allow users to select empty ratings?</strong>
              </p>
              <p>
                To allow users to select empty ratings simply set the `allowEmpty`
                setting to `true` or alternatively include an option with an empty value
                in your select field:
              </p>

<pre>
<code>
&lt;select id=&quot;example&quot;&gt;
  &lt;option value=&quot;&quot;&gt;&lt;/option&gt;
  &lt;option value=&quot;1&quot;&gt;1&lt;/option&gt;
  &lt;option value=&quot;2&quot;&gt;2&lt;/option&gt;
  &lt;option value=&quot;3&quot;&gt;3&lt;/option&gt;
&lt;/select&gt;
</code>
</pre>
            </div>
            <div  class="question">
              <p>
                <strong>How can I specify text that is displayed on the bars?</strong>
              </p>
              <p>
                The `showValues` plugin setting already makes it easy to display
                option values directly on the bars. For more control over the text
                that is displayed on the bars you can define `data-html` attribute
                on each of the &lt;option&gt; elements that will take precedence
                over actual option value.
              </p>

<pre>
<code>
&lt;select id=&quot;example&quot;&gt;
  &lt;option value=&quot;1&quot; data-html=&quot;good&quot;&gt;1&lt;/option&gt;
  &lt;option value=&quot;2&quot; data-html=&quot;better&quot;&gt;2&lt;/option&gt;
  &lt;option value=&quot;3&quot; data-html=&quot;best&quot;&gt;3&lt;/option&gt;
&lt;/select&gt;
</code>
</pre>
            </div>
            <div class="question">
              <p>
                <strong>How do I use the onSelect callback?</strong>
              </p>
              <p>
                The onSelect callback is fired when a user selects a rating or when the
                `set` method is called to select a rating programmatically. The optional
                third argument will give you access to the event object if it's available.
              </p>

<pre>
<code>
$('#example').barrating('show', {
  theme: 'my-awesome-theme',
  onSelect: function(value, text, event) {
    if (typeof(event) !== 'undefined') {
      // rating was selected by a user
      console.log(event.target);
    } else {
      // rating was selected programmatically
      // by calling `set` method
    }
  }
});
</code>
</pre>
              <p>
                Often you don't want the callback to run at all when a rating is selected
                with the `set` method. If this is the case please use the <a href="#nav-configuration">
                `silent`</a> option.
              </p>
            </div>
            <div  class="question">
              <p>
                <strong>Is keyboard navigation supported?</strong>
              </p>
              <p>
                Since the rating widget consists of anchor elements, you can tab or shift-tab between
                elements and press `Enter` to select a rating.
              </p>
            </div>
            <div class="question">
              <p>
                <strong>Can the plugin be used outside of Javascript ecosystem?</strong>
              </p>
              <p>
                Harvey Lieberman (<a href="https://github.com/harveyl888" target="_blank">@harveyl888</a>) created
                an R htmlwidget wrapper for the jQuery Bar Rating plugin. For more details go to:
              </p>
              <p>
                <a href="https://github.com/harveyl888/barRating" target="_blank">
                  https://github.com/harveyl888/barRating</a>
              </p>
            </div>
          </div>
        </div>
        <div class="license" id="nav-license">
          <header>License</header>
          <div class="instructions">
            <p>This plugin is available under the MIT license:</p>
            <p>
              <a href="http://www.opensource.org/licenses/mit-license.php" target="_blank">
                http://www.opensource.org/licenses/mit-license.php</a>
            </p>
          </div>
        </div>
        <div class="download" id="nav-download">
          <header>Download</header>
          <div class="instructions">
            <p>Download it at GitHub:</p>
            <p>
              <a href="http://github.com/antennaio/jquery-bar-rating" target="_blank">
                http://github.com/antennaio/jquery-bar-rating</a>
            </p>
          </div>
        </div>
      </div>
    </section>

    <a href="http://antenna.io" class="antennaio" target="_blank">
      <span>Made by ANTENNA.IO</span>
    </a>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.11.2.min.js"><\/script>')</script>

    <script src="starrating/jquery.barrating.js"></script>
    <script src="starrating/js/examples.js"></script>
  </body>
</html>
