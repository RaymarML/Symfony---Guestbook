import {h} from 'preact';
import {findComments} from '../api/api';
import {useState, useEffect} from 'preact/hooks';

function Comment({comments}) {
    if (comments !== null && comments.length === 0) {
        return <div className="text-center pt-4">No comments yet</div>;
    }

    if (!comments) {
        return <div className="text-center pt-4">Loading...</div>;
    }
    
    return (
        <div className="pt-4">
            {comments.map(comment => (
                <div className="shadow border rounded-lg p-3 mb-4">
                    <h5 className="font-weight-light mt-3 mb-0">{comment.author}</h5>
                    <div className="comment-text">{comment.text}</div>
                </div>
            ))}
        </div>
    );
}

export default function Conference({conferences, slug}) {
    const conference = conferences.find(conference => conference.slug ===slug);
    const [comments, setComments] = useState(null);

    useEffect(() => {
        findComments(conference).then(comments => setComments(comments));
    }, [slug]);
    
    return (
        <div className="p-3">
            <h4>{conference.city} {conference.year}</h4>
            <Comment comments={comments} />
        </div>
    );
};