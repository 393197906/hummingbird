var TopicPage = React.createClass({
    displayName: "TopicPage",

    render: function () {
        return React.createElement(
            "div",
            null,
            React.createElement(Topic, { data: this.props.data.topic }),
            React.createElement(Comments, { data: this.props.data.comments })
        );
    }
});
var Topic = React.createClass({
    displayName: "Topic",

    render: function () {
        return React.createElement(
            "div",
            { className: "row detail-topic" },
            React.createElement(
                AuthorInfo,
                { headpic: this.props.data.headpic, timestamp: this.props.data.posttime, type: this.props.data.ftype },
                this.props.data.username
            ),
            React.createElement(
                TopicContent,
                { length: 300 },
                React.createElement(
                    "strong",
                    { className: "text-info" },
                    "#",
                    this.props.data.type,
                    "  "
                ),
                this.props.data.content
            ),
            React.createElement(TopicInfo, { view: this.props.data.view, rise: this.props.data.rise })
        );
    }
});

var AuthorInfo = React.createClass({
    displayName: "AuthorInfo",

    render: function () {
        return React.createElement(
            "div",
            null,
            React.createElement(Img48, { src: ROOT_PATH + "/Uploads/headpic" + this.props.headpic }),
            this.props.children,
            "  ",
            React.createElement(ListDateTime, { timestamp: this.props.timestamp })
        );
    }

});

var Img48 = React.createClass({
    displayName: "Img48",

    render: function () {
        return React.createElement("img", { src: this.props.src, className: "img32" });
    }

});
var ListDateTime = React.createClass({
    displayName: "ListDateTime",

    render: function () {
        var timestamp = this.props.timestamp; //对时间进行处理，如2分钟前，三小时前
        var datetime = new Date(parseInt(timestamp) * 1000).toLocaleString().substr(0, 11);

        var now = Date.parse(new Date()) / 1000;
        var time = now - timestamp;
        var days = parseInt(time / 3600 / 24);
        if (days > 0) {
            timeformat = datetime;
        } else {
            var hours = parseInt(time / 3600);
            if (hours > 0) {
                timeformat = hours + "小时前";
            } else {
                var seconds = parseInt(time / 60);
                if (seconds > 0) {
                    timeformat = seconds + "分钟前";
                }
            }
        }

        return React.createElement(
            "span",
            { className: "founderlist-address" },
            timeformat
        );
    }

});

var TopicContent = React.createClass({
    displayName: "TopicContent",

    render: function () {
        var length = this.props.length ? this.props.length : 50; //默认长度
        var content = this.props.children;
        if (content.length > length) {
            content = content.substr(0, length) + "...";
        }
        return React.createElement(
            "div",
            null,
            content
        );
    }
});

var TopicInfo = React.createClass({
    displayName: "TopicInfo",

    render: function () {
        return React.createElement(
            "div",
            { className: "text-center" },
            React.createElement(
                "div",
                { className: "col-xs-6 nopad" },
                React.createElement(
                    IconTextView,
                    { code: "iconfont icon-liulanliang", "class": "text-muted" },
                    this.props.view
                )
            ),
            React.createElement(
                "div",
                { className: "col-xs-6 nopad" },
                React.createElement(
                    BtnRise,
                    null,
                    this.props.rise
                )
            )
        );
    }

});

var BtnRise = React.createClass({
    displayName: "BtnRise",

    render: function () {
        return React.createElement(
            IconTextView,
            { code: "iconfont icon-zan", "class": "text-danger" },
            this.props.children
        );
    }
});

var IconTextView = React.createClass({
    displayName: "IconTextView",

    render: function () {
        return React.createElement(
            "span",
            { className: this.props.class },
            React.createElement("span", { className: this.props.code }),
            React.createElement(
                "span",
                null,
                this.props.children
            )
        );
    }

});

var Comments = React.createClass({
    displayName: "Comments",

    render: function () {
        var commentRow = this.props.data.map(function (row, item) {
            return React.createElement(CommentRow, { data: row });
        });
        return React.createElement(
            "div",
            { className: "comments well well-sm" },
            commentRow
        );
    }
});

var CommentRow = React.createClass({
    displayName: "CommentRow",

    render: function () {
        return React.createElement(
            "div",
            { className: "commentrow" },
            React.createElement(
                AuthorInfo,
                { headpic: this.props.data.headpic, timestamp: this.props.data.commenttime },
                this.props.data.username
            ),
            React.createElement(
                "div",
                null,
                this.props.data.content
            )
        );
    }

});

ReactDOM.render(React.createElement(TopicPage, { data: data }), document.getElementById('content'));