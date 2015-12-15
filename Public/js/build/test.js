var Show = React.createClass({
    displayName: "Show",

    render: function () {
        return React.createElement("div", { children: "123" });
    }

});

ReactDOM.render(React.createElement(Show, null), document.getElementById('content'));