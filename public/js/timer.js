// Create Countdown
var Countdown = {

    // Backbone-like structure
    $el: $('.countdown'),

    // Params
    countdown_interval: null,
    total_seconds     : 0,
    limit             : -1,

    // Initialize the countdown
    init: function() {

        // DOM
        this.$ = {
            hours  : this.$el.find('.bloc-time.hours .figure'),
            minutes: this.$el.find('.bloc-time.min .figure'),
            seconds: this.$el.find('.bloc-time.sec .figure')
        };

        // Init countdown values
        this.values = {
            hours  : this.$.hours.parent().attr('data-init-value'),
            minutes: this.$.minutes.parent().attr('data-init-value'),
            seconds: this.$.seconds.parent().attr('data-init-value'),
        };

        // Initialize total seconds
        this.total_seconds = parseInt(this.values.hours) * 60 * 60 + (parseInt(this.values.minutes) * 60) + parseInt(this.values.seconds);
        // Animate countdown to the end
        this.count();
    },

    count: function() {

        var that    = this,
            $hour_1 = this.$.hours.eq(0),
            $hour_2 = this.$.hours.eq(1),
            $min_1  = this.$.minutes.eq(0),
            $min_2  = this.$.minutes.eq(1),
            $sec_1  = this.$.seconds.eq(0),
            $sec_2  = this.$.seconds.eq(1);

        this.countdown_interval = setInterval(function() {

            if(that.total_seconds > 0) {

                --that.values.seconds;

                if(that.values.minutes >= 0 && that.values.seconds < 0) {

                    that.values.seconds = 59;
                    --that.values.minutes;
                }

                if(that.values.hours >= 0 && that.values.minutes < 0) {

                    that.values.minutes = 59;
                    --that.values.hours;
                }

                // Update DOM values
                // Hours
                that.checkHour(that.values.hours, $hour_1, $hour_2);

                // Minutes
                that.checkHour(that.values.minutes, $min_1, $min_2);

                // Seconds
                that.checkHour(that.values.seconds, $sec_1, $sec_2);

                --that.total_seconds;
            }
            else {
                document.getElementById("submitExam").click();
            }
        }, 1000);
    },

    animateFigure: function($el, value) {

        var that         = this,
            $top         = $el.find('.top'),
            $bottom      = $el.find('.bottom'),
            $back_top    = $el.find('.top-back'),
            $back_bottom = $el.find('.bottom-back');

        // Before we begin, change the back value
        $back_top.find('span').html(value);

        // Also change the back bottom value
        $back_bottom.find('span').html(value);

        // Then animate
        TweenMax.to($top, 0.8, {
            rotationX           : '-180deg',
            transformPerspective: 300,
            ease                : Quart.easeOut,
            onComplete          : function() {

                $top.html(value);

                $bottom.html(value);

                TweenMax.set($top, { rotationX: 0 });
            }
        });

        TweenMax.to($back_top, 0.8, {
            rotationX           : 0,
            transformPerspective: 300,
            ease                : Quart.easeOut,
            clearProps          : 'all'
        });
    },

    checkHour: function(value, $el_1, $el_2) {

        var val_1       = value.toString().charAt(0),
            val_2       = value.toString().charAt(1),
            fig_1_value = $el_1.find('.top').html(),
            fig_2_value = $el_2.find('.top').html();

        if(value >= 10) {

            // Animate only if the figure has changed
            if(fig_1_value !== val_1) this.animateFigure($el_1, val_1);
            if(fig_2_value !== val_2) this.animateFigure($el_2, val_2);
        }
        else {

            // If we are under 10, replace first figure with 0
            if(fig_1_value !== '0') this.animateFigure($el_1, 0);
            if(fig_2_value !== val_1) this.animateFigure($el_2, val_1);
        }
    },

    decodeHTML: function(html) {
        var txt = document.createElement('textarea');
        txt.innerHTML = html;
        return txt.value;
    },

    makePenalization: function (data) {
        data = this.decodeHTML(data);
        let penalty = JSON.parse(data);

        if(penalty.type !== 'without') {
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post("/examgenerator/exam/increase_penalty");
        }

        switch (penalty.type) {
            case 'time':
                this.penalizesTime(penalty.body.minutes, penalty.body.seconds);
                break;
            case 'limitations':
                if(this.limit === -1) {
                    this.limit = penalty.body.limit;
                }
                this.penalizesLimit(penalty);
                break;
            case 'end':
                document.getElementById("is_forced").value = 1;
                document.getElementById("submitExam").click();
                break;
            default:
                break;
        }

    },

    penalizesTime: function(minutes, seconds) {
        var that = this;

        that.values.seconds = that.values.seconds - parseInt(seconds);
        that.values.minutes = that.values.minutes - parseInt(minutes);
        that.total_seconds = that.total_seconds - (parseInt(minutes) * 60 + parseInt(seconds));

        if(that.total_seconds <= 0) {
            document.getElementById("is_forced").value = 1;
            document.getElementById("submitExam").click();
        }

        if(that.values.minutes >= 0 && that.values.seconds < 0) {
            that.values.seconds = (that.values.seconds + 60) % 60;
            --that.values.minutes;
        }

        if(that.values.hours >= 0 && that.values.minutes < 0) {
            that.values.minutes = (that.values.minutes + 60) % 60;
            --that.values.hours;
        }
    },

    penalizesLimit: function(penalty) {
        if(this.limit > 0) {
            if(penalty.body.warnings) {
                $("#fraudTheExam").modal();
                this.limit--;
            }
        }
        else {
            switch (penalty.body.exceeded.type) {
                case 'time':
                    this.penalizesTime(penalty.body.exceeded.minutes, penalty.body.exceeded.seconds);
                    break;
                case 'end':
                    document.getElementById("is_forced").value = 1;
                    document.getElementById("submitExam").click();
                    break;
                default:
                    break;
            }
        }
    }
};


