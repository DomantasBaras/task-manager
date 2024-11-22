<template>
  <ul>
    <li 
      v-for="comment in comments" 
      :key="comment.id"
      class="comment-wrapper"
    >
      <div class="comment" :class="{ reply: type === 'reply' }">
        <span class="icon">
          <i :class="icon"></i>
        </span>
        <div>
          <p><strong>{{ comment.username }}</strong>: {{ comment.comment }}</p>
          <button @click="$emit('replyTo', comment)">Reply</button>
        </div>
      </div>

      <!-- Recursively render replies -->
      <CommentThread 
        v-if="comment.replies && comment.replies.length" 
        :comments="comment.replies" 
        type="reply"
        @addComment="$emit('addComment', $event, comment.id)"
        @replyTo="$emit('replyTo', $event)"
      />
    </li>
  </ul>
</template>

<script>
export default {
  name: 'CommentThread',
  props: {
    comments: Array,
    type: {
      type: String,
      required: false,
      default: 'comment',
    },
  },
  computed: {
    icon() {
      return this.type === 'reply' ? 'fas fa-reply' : 'fas fa-comment';
    },
  },
};
</script>

<style scoped>
.comment-wrapper {
  list-style: none;
  padding-left: 0;
}

.comment {
  display: flex;
  align-items: center;
  border: 1px solid #ddd;
  border-radius: 5px;
  padding: 10px;
  margin-bottom: 10px;
  position: relative;
}

.comment.reply {
  background-color: #f9f9f9;
}

.icon {
  margin-right: 10px;
}

.comment-replies {
  padding-left: 3.5rem;
  position: relative;
}

.comment-replies:before {
  background-color: SlateGray;
  content: '';
  height: calc(100% + 1rem);
  left: 1rem;
  position: absolute;
  top: 0;
  width: 1px;
}

.comment-replies:last-child:before {
  height: calc(100% - 1rem);
}

.comment.reply:before {
  background-color: Silver;
  content: '';
  height: 1px;
  left: -2.5rem;
  position: absolute;
  top: 50%;
  width: 0.75rem;
}
</style>
