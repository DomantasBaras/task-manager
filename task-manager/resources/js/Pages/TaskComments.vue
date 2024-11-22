<template>
  <div>
    <h1>Comments</h1>
    
    <!-- Render the top-level comments thread -->
    <CommentsThread 
      :comments="comments" 
      :taskId="taskId" 
      @addComment="addComment"
      @replyTo="setReplyTarget"
    />

    <!-- Single reply form at the bottom -->
    <div class="comment-form">
      <textarea 
        v-model="newComment" 
        placeholder="Write a comment..."
      ></textarea>
      <button class="primary-button" @click="submitComment">Submit</button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import CommentsThread from './CommentsThread.vue';
import { usePage } from '@inertiajs/vue3';
import { ref} from 'vue';
export default {
  components: { CommentsThread },
  props: {
    taskId: String,
    comments: Array,
  },
  data() {
    const username = ref(usePage().props.auth?.user?.name); 
    return {
      replyingTo: null,   
      newComment: '', 
      username : username
    };
  },
  methods: {
    // This method is called when the reply button is clicked
    setReplyTarget(comment) {
      this.replyingTo = comment;  
      this.newComment = `@${comment.comment} `;  
    },

    submitComment() {
      if (!this.newComment.trim()) {
        alert('Comment cannot be empty.');
        return;
      }

      const payload = {
        task_id: this.taskId,
        comment: this.newComment,
        parent_id: this.replyingTo ? this.replyingTo.id : null,  
        username: this.username
      };

      // Send the comment to the backend (using axios here)
      axios.post(`/api/tasks/${this.taskId}/comments`, payload,{ withCredentials: true,
          headers: {
            Authorization: `Bearer ${this.$page.props.auth.token}`,
          }, })
          
        .then((response) => {
          const savedComment = response.data;
          this.addComment(savedComment, this.replyingTo?.id || null);
          this.newComment = '';
          this.replyingTo = null;
        })
        .catch((error) => {
          console.error('Failed to submit comment:', error);
        });
    },

    // Method to add the new comment to the comments array (update the state)
    addComment(comment, parentId) {
      if (parentId) {
        const parentComment = this.findCommentById(this.comments, parentId);
        parentComment.replies = parentComment.replies || [];
        parentComment.replies.push(comment);
      } else {
        this.comments.push(comment);
      }
    },

    // Recursive helper function to find the comment by ID
    findCommentById(comments, id) {
      for (const comment of comments) {
        if (comment.id === id) return comment;
        if (comment.replies) {
          const found = this.findCommentById(comment.replies, id);
          if (found) return found;
        }
      }
      return null;
    },
  },
};
</script>

<style scoped>
.comment-form {
  margin-top: 20px;
}

textarea {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
}

.primary-button {
  display: inline-block;
  padding: 0.5em 1.7em;
  margin: 0 0.1em 0.1em 0;
  border: 0.16em solid rgba(255, 255, 255, 0);
  background-color: rgb(80, 10, 172);
  border-radius: 2em;
  box-sizing: border-box;
  text-decoration: none;
  font-family: "Roboto", sans-serif;
  font-weight: 300;
  color: #ffffff;
  text-shadow: 0 0.04em 0.04em rgba(0, 0, 0, 0.35);
  text-align: center;
  transition: all 0.2s;
}

.primary-button:hover {
  border-color: rgb(255, 255, 255);
}

.primary-button:focus {
  outline: none;
}


</style>
