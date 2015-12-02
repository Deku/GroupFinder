var data = [
    {author: "Pete Hunt", text: "This is one comment"},
    {author: "Jordan Walke", text: "This is *another* comment"}
];

var Comment = React.createClass({
    rawMarkup: function() {
        var rawMarkup = marked(this.props.children.toString(), {sanitize: true});
        return { __html: rawMarkup };
    },

    render: function() {
        return (
            <div className="media comment_section wow fadeInDown">
                <div className="pull-left post_comments hidden-xs hidden-sm">
                    <a href={"../../users/u/"+this.props.userid}>
                        <img src={this.props.image} className="img-circle" alt={this.props.author} />
                    </a>
                </div>
                <div className="media-body post_reply_comments">
                    <div className="col-xs-12">
                        <h4><a href={"../../users/u/"+this.props.userid}>{this.props.author}</a> dijo:</h4>
                        <p><span dangerouslySetInnerHTML={this.rawMarkup()} /></p>
                        <span className="text-muted">
                            <i className="fa fa-clock-o"></i> <TimeAgo date={this.props.time} />
                        </span>
                    </div>
                </div>
            </div>
        );
    }
});

var CommentList = React.createClass({
    render: function() {
        var commentNodes = this.props.data.map(function (comment) {
            return (
                <Comment key={comment.comment_id} author={comment.name} image={comment.img_src} userid={comment.user_id} time={comment.post_time}>
                    {comment.text}
                </Comment>
            );
        });

        return (
            <div className="commentList">
                {commentNodes}
            </div>
        );
    }
});

var CommentForm = React.createClass({
    handleSubmit: function(e) {
        e.preventDefault();
        var message = this.refs.message.value.trim();
        if (!message) {
            return;
        }

        this.props.onCommentSubmit({message: message});

        this.refs.message.value = '';
        return;
    },

    render: function() {
        return (
            <form className="contact-form" onSubmit={this.handleSubmit}>
                <div className="row">
                    <div className="col-sm-10">
                        <div className="form-group">
                            <textarea ref="message" required className="form-control" rows="6"></textarea>
                        </div>
                        <div className="form-group">
                            <button id="comment-submit" type="submit" className="btn btn-primary btn-lg pull-right" required="required">Enviar</button>
                        </div>
                    </div>
                </div>
            </form>
        );
    }
});

var CommentBox = React.createClass({
    getInitialState: function() {
        return {data: []};
    },

    loadCommentsFromServer: function() {
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            cache: false,
            success: function(data) {
                this.setState({data: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },

    componentDidMount: function() {
        this.loadCommentsFromServer();
        setInterval(this.loadCommentsFromServer, this.props.pollInterval);
    },

    handleCommentSubmit: function(comment) {
        $.ajax({
            url: SITE_URL + '/comments/post',
            dataType: 'json',
            type: 'POST',
            data: comment,
            success: function(data) {
                this.setState({data: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },

    render: function() {
        return (
            <div className="comments">
                <h2>Comentarios</h2>
                <CommentList data={this.state.data} />
                <CommentForm onCommentSubmit={this.handleCommentSubmit} />
            </div>
        );
    }
})

ReactDOM.render(
<CommentBox url="http://localhost/GroupFinder/comments/comments" pollInterval={5000} />,
    document.getElementById('feedback')
);
