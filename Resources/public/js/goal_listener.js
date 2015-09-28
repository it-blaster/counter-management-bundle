var GoalListener = function () {
    var self = this;
    $(window).on('reach.goal', function(event, goal, action) {
        self.YandexMetrikaReachGoal(goal);
        self.GoogleAnalyticsReachGoal(goal, action);
    });
};

GoalListener.prototype.YandexMetrikaReachGoal = function (goal) {
    if (typeof yandex_metrika_counters !== 'undefined') {
        $.each(yandex_metrika_counters, function (index, counter) {
            counter.reachGoal(goal);
        });
    }
};

GoalListener.prototype.GoogleAnalyticsReachGoal = function (goal, action) {
    ga(function () {
        $.each(ga.getAll(), function (index, tracker) {
            console.log(tracker);
            tracker.send(goal, action)
        });
    });
};


$(function(){
    new GoalListener();
});
