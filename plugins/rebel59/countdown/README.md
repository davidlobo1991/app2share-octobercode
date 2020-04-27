# Countdown
A simple yet powerful plugin to display countdowns on your OctoberCMS website.

### Dependencies
- OctoberCMS framework for the Ajax Call: `{% framework extras %}`
- jQuery (Tested with 3.2.0, should work with others.)
- [jQuery.Countdown](http://hilios.github.io/jQuery.countdown/)

### Usage

Drag and drop the `Countdown` component into your page and set the date property. This property should match the format of a Javascript DateTime object (`YYYY/MM/DD HH:MM:SS`).

You can choose which libraries you would like to load, to prevent duplicate asset loading. 

If you want to customise the countdown parameters see the jQuery.Countdown documentation. The date is loaded through an Ajax call `onCountdownDate`. 

    $.request('onCountdownDate',{
        success: function(data){
            $('#countdown').countdown(data.date).on('update.countdown', function(event) {
                // do countdown related things here.
                $('#countdown').addClass('show');
            });
        }
    })

---

#### Issues or other problems
Yikes. Found a problem with this plugin? Submit an issue and I'll look at it ASAP. If you fix it yourself and submit a PR I'll send you a cookie.

#### Want a feature?
Submit a pull request or submit an issue. I will get back to you as soon as possible to discuss if we can work something out.

#### Copyright
Developed for [Rebel59](https://rebel59.nl) by @CptMeatball. 
Plugin may be altered in anyway, provided you link back to this repo.
