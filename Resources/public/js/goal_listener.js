var GoalListener = function () {
    var self = this;
    $(window).on('reach.goal', function(event, goal) {
        self.YandexMetrikaReachGoal(goal);
        self.GoogleAnalyticsReachGoal(goal);
    });
};

GoalListener.prototype.YandexMetrikaReachGoal = function (goal) {
    if (typeof yandex_metrika_counters !== 'undefined') {
        $.each(yandex_metrika_counters, function (index, counter) {
            counter.reachGoal(goal);
        });
    }
};

GoalListener.prototype.GoogleAnalyticsReachGoal = function (goal) {
    ga(function () {
        $.each(ga.getAll(), function (tracker) {
            tracker.send(goal)
        });
    });
};


$(function(){
    new GoalListener();
});
