<template>
  <MainLayout>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <!-- Loop through task statuses dynamically -->
      <div
        v-for="(tasks, status) in tasksByStatus"
        :key="status"
        class="flex flex-col items-center bg-white rounded-lg shadow-lg p-4"
      >
        <h3 class="text-xl md:text-2xl font-bold mb-4 text-center capitalize">
          {{ status }}
        </h3>
        <div
          v-for="(task, index) in tasks"
          :key="status + '-' + index"
          class="task-card flex flex-col items-center w-full bg-gray-50 rounded-md shadow-sm p-4 hover:shadow-md transition-shadow mb-4"
          @click="goToTaskDetails(task.id)"
        >
          <div
            :class="[
              'icon-wrapper rounded-full text-white p-4 mb-3',
              statusClasses[status],
            ]"
          >
            <i :class="statusIcons[status]"></i>
          </div>
          <div class="text-center">
            <h5 class="text-lg font-semibold">{{ task.title }}</h5>
            <h6 class="text-sm text-gray-500">{{ status }}</h6>
          </div>
        </div>
      </div>
    </div>
  </MainLayout>
</template>


<script>
import MainLayout from "@/Layouts/MainLayout.vue";
export default {
  components: {
    MainLayout,
  },
  data() {
    return {
      tasks: [], // Array to hold tasks fetched from the API
      tasksByStatus: {
        paused: [],
        'in progress': [],
        testing: [],
        released: [],
      },
      statusClasses: {
        paused: 'bg-gray-500',
        'in progress': 'bg-yellow-500',
        testing: 'bg-blue-500',
        released: 'bg-green-500',
      },
      statusIcons: {
        paused: 'fas fa-pause',
        'in progress': 'fas fa-spinner',
        testing: 'fas fa-flask',
        released: 'fas fa-check',
      },
    };
  },
  mounted() {
    this.fetchTasks();
  },
  methods: {
    async fetchTasks() {
      try {
        const response = await fetch(`/api/tasks`); // Replace with your API endpoint
        const data = await response.json();
        this.tasks = data;
        this.sortTasksByStatus();
      } catch (error) {
        console.error("Error fetching tasks:", error);
      }
    },
    sortTasksByStatus() {
      this.tasks.forEach((task) => {
        if (this.tasksByStatus[task.status]) {
          this.tasksByStatus[task.status].push(task);
        }
      });
    },
    goToTaskDetails(taskId) {
      this.$inertia.get(`/tasks/${taskId}`);
    },
  },
};
</script>


<style scoped>
/* Grid layout */
.grid {
  display: grid;
  grid-template-columns: repeat(1, 1fr);
  gap: 1.5rem;
}

@media (min-width: 768px) {
  .grid {
    grid-template-columns: repeat(4, 1fr);
  }
}

/* Task Card Styling */
.task-card {
  background-color: #ffffff;
  border: 1px solid #e5e5e5;
  border-radius: 8px;
  transition: box-shadow 0.2s ease-in-out;
  cursor: pointer;
}
.task-card:hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.icon-wrapper {
  font-size: 1.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Responsive Typography */
h3 {
  color: rgb(80, 10, 172);
}

h5 {
  color: #333;
}

h6 {
  color: #555;
}
</style>

