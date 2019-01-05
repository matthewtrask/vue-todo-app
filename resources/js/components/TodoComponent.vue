<style lang='scss' scoped>
    p, h1, h2, h3, h4, h5, h6, a, td {
        letter-spacing: 0.05em;
        font-weight: 300;
    }

    img {
        width: 1rem;
        height: 1rem;
    }

    .modal {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        display: table;
        transition: opacity .3s ease;
    }
</style>
<template>
    <div>
        <div class="row">
            <div class="col-lg">
                <h2 class="text-center">Todos</h2>
                <div class="pb-4">
                    <button class="btn btn-primary" @click="showAddTodoModal"><img src="../../../public/icons/add-outline.svg" alt="Add Todo"> Add Todo</button>
                </div>
                <success-banner :msg="successMessage" v-if="showSuccessBanner === true"></success-banner>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">#</th>
                            <th scope="col">Todo</th>
                            <th scope="col">Notes</th>
                            <th scope="col">Completed At</th>
                            <th scope="col" class="text-center">Edit</th>
                            <th scope="col" class="text-center">Mark Done</th>
                            <th scope="col" class="text-center">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in todos">
                            <th scope="row">{{ index + 1 }}</th>
                            <td>{{ item.todo }}</td>
                            <td>{{ item.notes }}</td>
                            <td v-if="item.completed === 1">{{ item.updated_at|time }}</td>
                            <td v-else>Not Completed</td>
                            <th><img @click="showEditTodoModal(index, item)" class="mx-auto d-block" src="../../../public/icons/edit-pencil.svg" alt="Edit Todo"></th>
                            <th><img @click="completeTodo(index, item.id)" class="mx-auto d-block" src="../../../public/icons/checkmark.svg" alt="Add Todo"></th>
                            <td><img @click.prevent="deleteTodo(index, item.id)" class="mx-auto d-block" src="../../../public/icons/close.svg" alt="Delete todo"></td>
                        </tr>
                    </tbody>
                </table>

                <transition name="modal">
                    <div class="mt-8 modal" tabindex="-1" role="dialog" v-if="showAddModal === true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Add Todo</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click.prevent="closeAddTodoModal">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="form-group">
                                            <label for="add-todo">Todo</label>
                                            <input type="text" class="form-control" id="add-todo" placeholder="Eat Hot Chicken" v-model="todo.todo">
                                        </div>
                                        <div class="form-group">
                                            <label for="add-note">Notes</label>
                                            <textarea class="form-control" id="add-note" rows="3" v-model="todo.notes"></textarea>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" @click.prevent="addTodo">Save changes</button>
                                    <buttonit type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeAddTodoModal">Close</buttonit>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>

                <transition name="modal">
                    <div class="mt-8 modal" tabindex="-1" role="dialog" v-if="showEditModal === true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Todo</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click.prevent="closeEditTodoModal">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form>
                                        <div class="form-group">
                                            <input hidden type="text" class="form-control" id="edit-id" v-model="todo.id">
                                        </div>
                                        <div class="form-group">
                                            <label for="edit-todo">Todo</label>
                                            <input type="text" class="form-control" id="edit-todo" v-model="todo.todo">
                                        </div>
                                        <div class="form-group">
                                            <label for="edit-note">Notes</label>
                                            <textarea class="form-control" id="edit-note" rows="3" v-model="todo.notes"></textarea>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" @click.prevent="editTodo">Save changes</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeEditTodoModal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>
<script>
  import axios from 'axios';
  import moment from 'moment';
  import SuccessBanner from './Partials/SuccessAlert.vue';
  import ErrorBanner from './Partials/ErrorAlert.vue';

  export default {
    created() {
      this.getTodos();
    },

    mounted() {

    },

    data() {
      return {
        todos: [],
        showSuccessBanner: false,
        successMessage: '',
        showError: false,
        errorMessage: '',
        showAddModal: false,
        showEditModal: false,
        todo: {
          id: '',
          todo: '',
          notes: '',
        }
      };
    },

    methods: {
      addTodo() {
        this.todos.push(this.todo);

        this.showAddModal = false;

        axios.post('/api/todos', {
          todo: this.todo.todo,
          notes: this.todo.notes,
        }).then(response => {
          this.showSuccessBanner = true;
          this.successMessage = 'The todo was added successfully';

          setTimeout(() => {
            this.showSuccessBanner = false;
          }, 4000);
        }).catch(error => {
          this.$delete(this.todos, this.todos.indexOf(this.todo));
          console.error(error);
        })
      },

      completeTodo(index, id) {
        axios.put(`/api/todos/${id}/completed`).then(response => {
          this.$delete(this.todos, index);

          this.showSuccessBanner = true;
          this.successMessage = 'The todo was marked as completed';
          setTimeout(() => {
            this.showSuccessBanner = false;
          }, 4000);

          this.getTodos();
        }).catch(error => {
          console.log(error);
        });
      },

      editTodo() {
        axios.put(`/api/todos/${this.todo.id}`, {
          todo: this.todo.todo,
          notes: this.todo.notes,
        }).then(response => {
          this.getTodos();
          this.showEditModal = false;
        }).catch(error => {
          console.log(error);
        })
      },

      deleteTodo(index, id) {
        this.$delete(this.todos, index);

        axios.delete(`/api/todos/${id}`).then(response => {
          this.showSuccessBanner = true;
          this.successMessage = 'The todo was removed from your list';

          setTimeout(() => {
            this.showSuccessBanner = false;
          }, 4000);
        })
      },

      showAddTodoModal() {
        this.showAddModal = true;
      },

      closeAddTodoModal() {
        this.showAddModal = false;
      },

      showEditTodoModal(index, item) {
        this.showEditModal = true;
        this.todo.id = item.id;
        this.todo.todo = item.todo;
        this.todo.notes = item.notes;
      },

      closeEditTodoModal() {
        this.showEditModal = false;
      },

      getTodos() {
        axios.get('/api/todos').then(response => {
          this.todos = response.data;
        })
      }
    },

    filters: {
      time(value) {
        return moment(value).format('m-d-Y');
      }
    },

    components: {
      SuccessBanner,
      ErrorBanner,
    },
  };
</script>