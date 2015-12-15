var FounderList = React.createClass({
    displayName: 'FounderList',

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
        return {
            data: []
        };
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
                { className: 'pull-left' },
                React.createElement(Image64, { src: ROOT_PATH + "/Uploads/headpic" + this.props.data.headpic })
            ),
            React.createElement(
                'div',
                { className: 'col-xs-9' },
                React.createElement(
                    ListTitle,
                    null,
                    React.createElement(
                        'strong',
                        null,
                        React.createElement(
                            'span',
                            null,
                            this.props.data.username
                        )
                    ),
                    '  ',
                    React.createElement(
                        'strong',
                        null,
                        React.createElement(
                            'span',
                            { className: 'text-primary' },
                            this.props.data.type
                        )
                    )
                ),
                React.createElement(
                    ListSubTitle,
                    null,
                    React.createElement(
                        FounderListAddress,
                        null,
                        this.props.data.province,
                        this.props.data.city
                    )
                ),
                React.createElement(
                    ListContent,
                    null,
                    this.props.data.intro
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
            null,
            this.props.children
        );
    }
});

var FounderListAddress = React.createClass({
    displayName: 'FounderListAddress',

    render: function () {
        return React.createElement(
            'span',
            { className: 'founderlist-address' },
            React.createElement('span', { className: 'glyphicon glyphicon-map-marker' }),
            this.props.children
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

ReactDOM.render(React.createElement(FounderList, null), document.getElementById('content'));