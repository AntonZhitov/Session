<template>
  <div id="app">
    <TaskList />
  </div>
</template>

<script setup>
import TaskList from './components/TaskList.vue'
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: Arial, sans-serif;
  background: #f5f5f5;
  padding: 20px;
}
</style>