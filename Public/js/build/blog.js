// TODO: 评论数量 和点赞数量
var BlogList = React.createClass({
    displayName: 'BlogList',

    getData: function () {
        $.ajax({
            url: CON_PATH + '/getData',
            type: 'post',
            dataType: 'json',
            cache: false,
            success: (function (data) {
                console.log(data);
                this.setState({ data: data });
            }).bind(this),
            error: (function (data, status, err) {
                console.log("error");
            }).bind(this),
            complete: function () {
                console.log("complete");
            }
        });
    },

    getInitialState: function () {
        return { data: [] };
    },

    componentDidMount: function () {
        this.getData();
    },
    render: function () {
        var founderRow = this.state.data.map(function (row, elem) {
            return React.createElement(FounderListRow, { data: row });
        });
        return React.createElement(
            'div',
            { className: 'founderList' },
            founderRow
        );
    }
});

var FounderListRow = React.createClass({
    displayName: 'FounderListRow',

    handlerTouch: function (e) {
        e.preventDefault();
        var url = CON_PATH + '/detail?id=' + this.props.data.id;
        location.href = url;
    },
    render: function () {
        return React.createElement(
            'div',
            { className: 'founderRow', onTouchEnd: this.handlerTouch },
            React.createElement(
                'div',
                { className: 'pull-left text-center' },
                React.createElement(Image64, { src: ROOT_PATH + "/Uploads/headpic" + this.props.data.headpic })
            ),
            React.createElement(
                'div',
                { className: 'col-xs-9' },
                React.createElement(
                    ListTitle,
                    null,
                    React.createElement(
                        'span',
                        { className: 'text-danger' },
                        '[',
                        this.props.data.type,
                        ']'
                    )
                ),
                React.createElement(
                    ListTitle,
                    null,
                    React.createElement(
                        'strong',
                        null,
                        React.createElement(
                            'span',
                            { className: 'text-primary' },
                            this.props.data.title
                        )
                    )
                ),
                React.createElement(
                    ListSubTitle,
                    null,
                    React.createElement(
                        'span',
                        null,
                        this.props.data.username,
                        ' '
                    ),
                    '发表于',
                    React.createElement(ListDateTime, { timestamp: this.props.data.posttime })
                )
            ),
            React.createElement('div', { className: 'clearfix' })
        );
    }
});

var Image64 = React.createClass({
    displayName: 'Image64',

    render: function () {
        return React.createElement('img', { className: 'img64', src: this.props.src });
    }
});

var ListTitle = React.createClass({
    displayName: 'ListTitle',

    render: function () {
        return React.createElement(
            'div',
            { className: 'listtitle' },
            this.props.children
        );
    }
});

var ListSubTitle = React.createClass({
    displayName: 'ListSubTitle',

    render: function () {
        return React.createElement(
            'div',
            { className: 'founderlist-address' },
            this.props.children
        );
    }
});

var ListDateTime = React.createClass({
    displayName: 'ListDateTime',

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
            'span',
            null,
            timeformat
        );
    }

});

var ListContent = React.createClass({
    displayName: 'ListContent',

    render: function () {
        var length = this.props.length ? this.props.length : 50; //默认长度
        var content = this.props.children;
        if (content.length > length) {
            content = content.substr(0, length) + "...";
        }
        return React.createElement(
            'div',
            { className: 'founderlist-content' },
            content
        );
    }

});

ReactDOM.render(React.createElement(BlogList, null), document.getElementById('content'));