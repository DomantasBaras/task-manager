<template>
  <main-layout>
    <template v-slot:default>
      <div v-if="entity" class="entity-detail-page">
        <!-- Left: Entity Information (Editable) -->
        <div v-if="entity && entity.title" class="entity-info-section">
          <p><strong>Title:</strong> 
            <h2>
              <input v-if="editing" v-model="editingEntity.title" class="entity-title-input" />
              <span v-else>{{ entity.title }}</span>
            </h2>
          </p>

          <p><strong>Description:</strong> 
            <div class="description-wrapper">
              <p>
                <textarea v-if="editing" v-model="editingEntity.description" class="entity-description-input"></textarea>
                <span v-else>{{ showFullDescription ? entity.description : truncatedDescription }}</span>
              </p>
              <button v-if="isDescriptionLong && !editing" @click="toggleDescription" class="btn-toggle-description">
                {{ showFullDescription ? "Show Less" : "Show More" }}
              </button>
            </div>
          </p>

          <p><strong>Creator:</strong> 
            <input v-if="editing" v-model="editingEntity.creator" class="entity-field-input" />
            <span v-else>{{ entity.creator }}</span>
          </p>

          <p><strong>Tester:</strong> 
            <input v-if="editing" v-model="editingEntity.tester" class="entity-field-input" />
            <span v-else>{{ entity.tester }}</span>
          </p>

          <p><strong>Assignee:</strong> 
            <input v-if="editing" v-model="editingEntity.assignee" class="entity-field-input" />
            <span v-else>{{ entity.assignee }}</span>
          </p>

          <p><strong>Status:</strong> 
            <span v-if="!editing">{{ entity.status }}</span>
            <select v-else v-model="editingEntity.status" class="status-select">
              <option value="paused">Paused</option>
              <option value="in progress">In Progress</option>
              <option value="testing">Testing</option>
              <option value="released">Released</option>
            </select>
          </p>

          <p><strong>Type:</strong> 
            <span v-if="!editing">{{ entity.type }}</span>
            <select v-else v-model="editingEntity.type" class="type-select">
              <option value="Task">Task</option>
              <option value="Bug">Bug</option>
            </select>
          </p>

          <!-- Action Buttons -->
          <div>
            <button v-if="!editing" @click="startEditing" class="primary-button">Edit Details</button>
            <button v-if="editing" @click="saveChanges" class="primary-button">Save Changes</button>
            <button v-if="editing" @click="cancelEditing" class="secondary-button">Cancel</button>
          </div>
        </div>

        <!-- Comments Section -->
        <div v-if="comments" class="entity-comments-section">
          <TaskComments 
            :taskId="entity.id"
            :comments="comments" 
            @addComment="addComment"
          />
        </div>
        <div v-else>
          <p>No comments yet. Be the first to comment!</p>
        </div>

        <!-- Logs Section -->
        <div class="entity-logs-section">
          <h3>Change Logs</h3>
          <ul v-if="logs.length">
            <li v-for="log in logs" :key="log.id">
              <p><strong>{{ log.field_changed }}</strong> changed from "{{ log.old_value }}" to "{{ log.new_value }}"</p>
              <p><small>Logged at: {{ log.created_at }}</small></p>
            </li>
          </ul>
          <p v-else>No change logs available.</p>
        </div>
      </div>

      <div v-else>
        <p>Loading...</p>
      </div>
    </template>
  </main-layout>
</template>


<script>
import MainLayout from "@/Layouts/MainLayout.vue";
import TaskComments from "./TaskComments.vue";
import axios from "axios";

export default {
  props: ["id"],
  components: {
    MainLayout,
    TaskComments
  },
  data() {
    return {
      entity: null,
      comments: [],
      newComment: "",
      newStatus: "",
      newType: "",
      editing: false,
      showFullDescription: false,
      logs: []
    };
  },
  computed: {
    truncatedDescription() {
      return this.entity.description.length > 150
        ? this.entity.description.substring(0, 150) + "..."
        : this.entity.description;
    },
    isDescriptionLong() {
      return this.entity.description.length > 150;
    },
  },
  async created() {
    this.fetchEntity();
    this.fetchComments();
    this.fetchLogs(); 
  },
  methods: {
    async fetchEntity() {
      try {
        const response = await fetch(`/api/tasks/${this.id}`);
        if (!response.ok) throw new Error("Failed to fetch entity");
        const data = await response.json();
        this.entity = data;

        // Prefill newStatus and newType with the current values from entity
        this.newStatus = data.status;
        this.newType = data.type;

      } catch (error) {
        console.error(error);
      }
    },

    async fetchComments() {
      try {
        const response = await fetch(`/api/tasks/${this.id}/comments`);
        if (!response.ok) throw new Error("Failed to fetch comments");
        const data = await response.json();
        console.log("Comments data:", data); // Log the response
        this.comments = data;
      } catch (error) {
        console.error(error);
      }
    },

    async fetchLogs() {
      try {
        const response = await fetch(`/api/tasks/${this.id}/logs`);
        if (!response.ok) throw new Error("Failed to fetch logs");
        const data = await response.json();
        console.log("Logs data:", data); // Log the response
        this.logs = data;
      } catch (error) {
        console.error(error);
      }
    },

    async changeStatus() {
      try {
        const response = await fetch(`/api/tasks/${this.entity.id}/status`, {
          method: "PATCH",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ status: this.newStatus })
        });

        if (!response.ok) throw new Error('Failed to update status');
        this.entity.status = this.newStatus;  // Update UI with the new status
        this.newStatus = '';  // Reset the status input
      } catch (error) {
        console.error("Error updating status:", error);
        alert("Error updating status.");
      }
    },

    async changeType() {
      try {
        const response = await fetch(`/api/tasks/${this.entity.id}/type`, {
          method: "PATCH",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify({ type: this.newType })
        });

        if (!response.ok) throw new Error('Failed to update type');
        this.entity.type = this.newType;  // Update UI with the new type
        this.newType = '';  // Reset the type input
      } catch (error) {
        console.error("Error updating type:", error);
        alert("Error updating type.");
      }
    },

    async saveChanges() {
      try {
        const response = await fetch(`/api/tasks/${this.entity.id}`, {
          method: "PUT",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(this.editingEntity),
        });
        if (!response.ok) throw new Error("Failed to save changes");

        const updatedEntity = await response.json();
        this.entity = updatedEntity; 
        this.editing = false;
        await this.fetchLogs;
      } catch (error) {
        console.error("Error saving changes:", error);
      }
    },
    
    toggleDescription() {
      this.showFullDescription = !this.showFullDescription;
    },

    updateEntity() {
      fetch(`/api/tasks/${this.id}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(this.entity),
      }).then((response) => {
        if (!response.ok) throw new Error("Failed to update entity");
        this.editing = false;
        this.fetchEntity(); // Refetch the entity to get the updated data
      }).catch((error) => {
        console.error(error);
      });
    },
    startEditing() {
      this.editingEntity = { ...this.entity }; // Clone the entity for editing
      this.editing = true;
    },
    cancelEditing() {
      this.editingEntity = null; // Discard changes
      this.editing = false;
    },
  },
};
</script>


<style scoped>
.entity-detail-page {
  display: flex;
  max-width: 1200px;
  margin: 0 auto;
  gap: 30px;
  padding: 20px;
  flex-wrap: wrap;
}
.status-select,
.type-select,
textarea,
input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
}
.entity-info-section,
.entity-comments-section,
.entity-logs-section,
.entity-action-section {
  flex: 1 1 300px;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.entity-title {
  font-size: 2rem;
  font-weight: bold;
}

.description-wrapper p {
  line-height: 1.6;
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
.secondary-button {
  display: inline-block;
  padding: 0.5em 1.7em;
  margin: 0 0.1em 0.1em 0;
  border: 0.16em solid rgb(80, 10, 172); /* Match the color of the primary button */
  background-color: #ffffff; /* White background */
  border-radius: 2em;
  box-sizing: border-box;
  text-decoration: none;
  font-family: "Roboto", sans-serif;
  font-weight: 300;
  color: rgb(80, 10, 172); /* Same color as the primary button border */
  text-shadow: none; /* No shadow for cleaner appearance */
  text-align: center;
  transition: all 0.2s;
}

.secondary-button:hover {
  background-color: rgb(80, 10, 172); /* Match primary button hover color */
  color: #ffffff; /* White text on hover */
}

.secondary-button:focus {
  outline: none;
}
.status-label,
.type-label {
  font-weight: bold;
}

.status-select,
.type-select {
  width: 100%;
  padding: 10px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.comment-textarea {
  width: 100%;
  height: 100px;
  padding: 10px;
  margin-top: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
  
}

.comment-item,
.reply-item {
  margin-bottom: 10px;
}

.comment-item p,
.reply-item p {
  margin: 5px 0;
}

.btn-reply,
.btn-toggle-description {
  background: none;
  border: none;
  color: #007bff;
  cursor: pointer;
  text-decoration: underline;
  padding: 5px;
}

.btn-reply:hover,
.btn-toggle-description:hover {
  text-decoration: none;
}

.show-more-btn {
  background: none;
  border: none;
  color: blue;
  cursor: pointer;
  text-decoration: underline;
}
.entity-description-input{
  width: 100%;
  height: 200px;
  margin-top: 6px;
}

</style>
