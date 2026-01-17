<template>
  <div class="task-list">
    <h1>ToDo List</h1>
    
    <!-- Форма добавления -->
    <div class="add-form">
      <input v-model="newTask.title" placeholder="Заголовок" class="input" />
      <textarea v-model="newTask.description" placeholder="Описание" class="textarea"></textarea>
      <button @click="addTask" class="btn btn-primary">Добавить</button>
    </div>

    <!-- Список задач -->
    <div v-if="tasks.length === 0" class="empty">Задач нет</div>
    
    <div v-else class="tasks">
      <div v-for="task in tasks" :key="task.id" class="task-card">
        <div class="task-header">
          <h3 :class="{ done: task.isDone }">{{ task.title }}</h3>
          <div class="task-actions">
            <button @click="toggleDone(task)" class="btn btn-small">
              {{ task.isDone ? 'Отменить' : 'Выполнено' }}
            </button>
            <button @click="editTask(task)" class="btn btn-small btn-edit">✏️</button>
            <button @click="deleteTask(task.id)" class="btn btn-small btn-danger">🗑️</button>
          </div>
        </div>
        <p>{{ task.description }}</p>
        <small>Создано: {{ formatDate(task.createdAt) }}</small>
      </div>
    </div>

    <!-- Модальное окно редактирования -->
    <div v-if="editingTask" class="modal">
      <div class="modal-content">
        <h2>Редактировать задачу</h2>
        <input v-model="editingTask.title" class="input" />
        <textarea v-model="editingTask.description" class="textarea"></textarea>
        <div class="modal-actions">
          <button @click="saveEdit" class="btn btn-primary">Сохранить</button>
          <button @click="cancelEdit" class="btn btn-secondary">Отмена</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { getTasks, createTask, updateTask, deleteTask as deleteTaskApi } from '../api.js'

const tasks = ref([])
const newTask = ref({ title: '', description: '' })
const editingTask = ref(null)

// Загрузка задач
const fetchTasks = async () => {
  tasks.value = await getTasks()
}

// Добавление
const addTask = async () => {
  if (!newTask.value.title.trim()) return
  await createTask(newTask.value)
  newTask.value = { title: '', description: '' }
  fetchTasks()
}

// Удаление
const deleteTask = async (id) => {
  if (!confirm('Удалить задачу?')) return
  await deleteTaskApi(id)
  fetchTasks()
}

// Редактирование
const editTask = (task) => {
  editingTask.value = { ...task }
}

const saveEdit = async () => {
  if (!editingTask.value.title.trim()) return
  await updateTask(editingTask.value.id, editingTask.value)
  editingTask.value = null
  fetchTasks()
}

const cancelEdit = () => {
  editingTask.value = null
}

// Отметка выполнения
const toggleDone = async (task) => {
  task.isDone = !task.isDone
  await updateTask(task.id, { isDone: task.isDone })
  fetchTasks()
}

// Форматирование даты
const formatDate = (dateString) => {
  return new Date(dateString).toLocaleString('ru-RU')
}

onMounted(() => {
  fetchTasks()
})
</script>

<style scoped>
.task-list {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.input, .textarea {
  width: 100%;
  margin-bottom: 10px;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.btn {
  padding: 8px 16px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-primary {
  background-color: #007bff;
  color: white;
}

.btn-secondary {
  background-color: #6c757d;
  color: white;
}

.btn-danger {
  background-color: #dc3545;
  color: white;
}

.btn-small {
  padding: 4px 8px;
  margin-left: 5px;
}

.task-card {
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 15px;
  margin-bottom: 10px;
  background: #f9f9f9;
}

.task-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.done {
  text-decoration: line-through;
  color: #888;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-content {
  background: white;
  padding: 20px;
  border-radius: 8px;
  min-width: 400px;
}

.modal-actions {
  margin-top: 15px;
  display: flex;
  gap: 10px;
}
</style>