// src/api.js
const API_BASE = 'http://localhost:8000/api/tasks'

export const getTasks = async () => {
  const response = await fetch(API_BASE)
  return response.json()
}

export const getTask = async (id) => {
  const response = await fetch(`${API_BASE}/${id}`)
  return response.json()
}

export const createTask = async (task) => {
  const response = await fetch(API_BASE, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(task)
  })
  return response.json()
}

export const updateTask = async (id, task) => {
  const response = await fetch(`${API_BASE}/${id}`, {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(task)
  })
  return response.json()
}

export const deleteTask = async (id) => {
  const response = await fetch(`${API_BASE}/${id}`, {
    method: 'DELETE'
  })
  return response.ok
}