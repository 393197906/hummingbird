var TopicPage = React.createClass({
    render: function() {
        return (
            <div>
                <Topic data={this.props.data.topic}></Topic>
                <Comments data={this.props.data.comments}></Comments>
            </div>

        );
    }
});
var Topic = React.createClass({
    render: function() {
        return (
            <div className="row detail-topic">
                <AuthorInfo headpic={this.props.data.headpic} timestamp={this.props.data.posttime} type={this.props.data.ftype}>
                    {this.props.data.username}
                </AuthorInfo>
                <TopicContent length={300}>
                    <strong className="text-info">
                        #{this.props.data.type}&nbsp;&nbsp;
                    </strong>
                    {this.props.data.content}
                </TopicContent>
                <TopicInfo view={this.props.data.view} rise={this.props.data.rise}></TopicInfo>
            </div>
        );
    }
});

var AuthorInfo = React.createClass({
    render: function() {
        return (
            <div>
                <Img48 src={ROOT_PATH + "/Uploads/headpic" + this.props.headpic}></Img48>
                {this.props.children}
                &nbsp;&nbsp;
                <ListDateTime timestamp={this.props.timestamp}></ListDateTime>
            </div>
        );
    }

});

var Img48 = React.createClass({
    render: function() {
        return (<img src={this.props.src} className="img32"/>);
    }

});
var ListDateTime = React.createClass({
    render: function() {
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

        return (
            <span className="founderlist-address">
                {timeformat}
            </span>
        );
    }

});

var TopicContent = React.createClass({
    render: function() {
        var length = this.props.length
            ? this.props.length
            : 50; //默认长度
        var content = this.props.children;
        if (content.length > length) {
            content = content.substr(0, length) + "...";
        }
        return (
            <div>
                {content}
            </div>
        );
    }
});

var TopicInfo = React.createClass({
    render: function() {
        return (
            <div className="text-center">
                <div className="col-xs-6 nopad">
                    <IconTextView code={"iconfont icon-liulanliang"} class="text-muted">
                        {this.props.view}
                    </IconTextView>
                </div>
                <div className="col-xs-6 nopad">
                    <BtnRise>{this.props.rise}</BtnRise>
                </div>
            </div>
        );
    }

});

var BtnRise = React.createClass({
    render: function() {
        return (
            <IconTextView code={"iconfont icon-zan"} class="text-danger">
                {this.props.children}
            </IconTextView>
        );
    }
});

var IconTextView = React.createClass({
    render: function() {
        return (
            <span className={this.props.class}>
                <span className={this.props.code}></span>
                <span>
                    {this.props.children}</span>
            </span>

        );
    }

});

var Comments = React.createClass({
    render: function() {
        var commentRow = this.props.data.map(function(row, item) {
            return (<CommentRow data={row}/>);
        });
        return (
            <div className="comments well well-sm">
                {commentRow}
            </div>
        );
    }
});

var CommentRow = React.createClass({
    render: function() {
        return (
            <div className="commentrow">
                <AuthorInfo headpic={this.props.data.headpic} timestamp={this.props.data.commenttime}>
                    {this.props.data.username}
                </AuthorInfo>
                <div>
                    {this.props.data.content}
                </div>
            </div>
        );
    }

});

ReactDOM.render(< TopicPage data = {
    data
} />, document.getElementById('content'));
