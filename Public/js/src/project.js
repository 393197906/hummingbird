var ProjectList = React.createClass({
    getData: function() {
        $.ajax({
            url: CON_PATH + '/getData',
            type: 'post',
            dataType: 'json',
            cache: false,
            success: function(data) {
                console.log(data);
                this.setState({data: data});
            }.bind(this),
            error: function(data, status, err) {
                console.log("error");
            }.bind(this),
            complete: function() {
                console.log("complete");
            }
        });
    },

    getInitialState: function() {
        return {
            data: []
        };
    },

    componentDidMount: function() {
        this.getData();
    },
    render: function() {
        var founderRow = this.state.data.map(function(row, elem) {
            return (
                <ProjectListRow data={row}/>
            );
        });
        return (
            <div className="founderList">
                {founderRow}
            </div>
        );
    }
});

var ProjectListRow = React.createClass({
    handlerTouch:function (e) {
        e.preventDefault();
        var url=CON_PATH+'/detail?id='+this.props.data.id;
        location.href=url;
    },
    render: function() {
        return (
            <div className="founderRow" onTouchEnd={this.handlerTouch}>
                <div className="pull-left">
                    <Image64 src={ROOT_PATH + "/Uploads/headpic" + this.props.data.headpic}></Image64>
                </div>
                <div className="col-xs-9">
                    <ListTitle>
                        <strong>
                            <span>{this.props.data.pname}</span>
                        </strong>
                        &nbsp;&nbsp;
                        <strong>
                            <span className="text-primary">{this.props.data.ptype}</span>
                        </strong>
                    </ListTitle>
                    <ListSubTitle>
                        <ProjectListAddress>
                            {this.props.data.location}
                        </ProjectListAddress>
                    </ListSubTitle>

                    <ListContent>{this.props.data.intro}</ListContent>

                </div>
                <div className="clearfix"></div>
            </div>
        );
    }

});


var Image64 = React.createClass({
    render: function() {
        return (
            <img className="img64" src={this.props.src}/>
        );
    }
});

var ListTitle = React.createClass({
    render: function() {
        return (
            <div className="listtitle">
                {this.props.children}
            </div>
        );
    }
});

var ListSubTitle = React.createClass({
    render: function() {
        return (
            <div>
                {this.props.children}
            </div>
        );
    }
});

var ProjectListAddress = React.createClass({
    render: function() {
        return (
            <span className="founderlist-address">
                <span className="glyphicon glyphicon-map-marker"></span>
                {this.props.children}
            </span>

        );
    }

});

var ListContent = React.createClass({
    render: function() {
        return (
            <div className="founderlist-content">
                {this.props.children}
            </div>
        );
    }

});


ReactDOM.render(
    <ProjectList/>, document.getElementById('content'));
