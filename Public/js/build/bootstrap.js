var data = [{
    "imgurl": "images/1.jpg",
    "title": "NilTor",
    "content": "简单的介绍内容"
}, {
    "imgurl": "images/2.png",
    "title": "NilTor",
    "content": "简单的介绍内容"
}, {
    "imgurl": "images/3.png",
    "title": "NilTor",
    "content": "简单的介绍内容"
}, {
    "imgurl": "images/4.jpg",
    "title": "NilTor",
    "content": "简单的介绍内容"
}];

var FounderList = React.createClass({
    displayName: "FounderList",

    render: function () {
        var founderRow = this.props.data.map(function (row, elem) {
            return React.createElement(
                "div",
                { className: "founderRow" },
                React.createElement(
                    "div",
                    { className: "pull-left" },
                    React.createElement(Image48, { src: row.imgurl })
                ),
                React.createElement(
                    "div",
                    { className: "col-xs-9" },
                    React.createElement(
                        ListTitle,
                        null,
                        row.title
                    ),
                    React.createElement(
                        ListContent,
                        null,
                        row.content
                    )
                ),
                React.createElement("div", { className: "clearfix" })
            );
        });
        return React.createElement(
            "div",
            { className: "founderList" },
            founderRow
        );
    }
});

var Image48 = React.createClass({
    displayName: "Image48",

    render: function () {
        return React.createElement("img", { className: "img48", src: this.props.src });
    }
});

var ListTitle = React.createClass({
    displayName: "ListTitle",

    render: function () {
        return React.createElement(
            "div",
            null,
            this.props.children
        );
    }

});

var ListContent = React.createClass({
    displayName: "ListContent",

    render: function () {
        return React.createElement(
            "div",
            null,
            this.props.children
        );
    }

});

ReactDOM.render(React.createElement(FounderList, { data: data }), document.getElementById('content'));