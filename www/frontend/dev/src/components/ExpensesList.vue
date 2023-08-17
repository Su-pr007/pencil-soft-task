<template>
    <div>
      <div class="expenses-list">
        <div>
          <div class="expenses-list__head">
            <div class="expenses-list__head-item">ID</div>
            <div class="expenses-list__head-item">Comment</div>
            <div class="expenses-list__head-item">Sum</div>
            <div class="expenses-list__head-item">Date</div>
            <div class="expenses-list__head-item">Action 1</div>
            <div class="expenses-list__head-item">Action 2</div>
          </div>
        </div>
        <div class="expenses-list__body">
          <ExpenseRow
          v-for="expense in expenses"
          :key="expense"
          :expense="expense"
          @changeRow="changeRow($event)"
          @deleteRow="removeRow($event)"
          @saveRow="saveRow($event)"></ExpenseRow>
  
        </div>
        <q-dialog v-model="isAddingExpense">
          <div class="dialog-container">
            <AddExpenseRow
                :newExpense="newExpense"
                @saveRow="saveNewRow($event)"
                @cancelSaveRow="cancelSaveRow()"></AddExpenseRow>
          </div>
        </q-dialog>
      </div>
      <input
        v-if="!isAddingExpense"
        @click="addExpenseRow"
        class="expenses-list__create-button" type="button" value="+" title="Добавить запись" />
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { Notify } from 'quasar';
  import AddExpenseRow from './AddExpenseRow.vue';
  import ExpenseRow from './ExpenseRow.vue';
  
  export default {
    components: {
      AddExpenseRow,
      ExpenseRow
    },
    data() {
      return {
        expenses: null,
        isAddingExpense: false,
        newExpense: {
          comment: '',
          sum: '',
          date: ''
        }
      }
    },
    created() {
      this.fillTable();
    },
    methods: {
      fillTable() {
        axios('/api/test/expense')
        .then(response => {
          this.expenses = response.data.map(expense => {
            expense.isChanges = false; // Добавляем свойство isChanges для каждого расхода
  
            return expense;
          });
        })
        .catch(error => {
          this.showNotification(error.response.data.notification);
        })
      },
      addExpenseRow() {
        this.isAddingExpense = true;
      },
      cancelSaveRow() {
        this.isAddingExpense = false;
      },
      saveNewRow(expense) {
        this.isAddingExpense = false;
  
        axios.post('/api/test/expense', this.getExpenseRowData(expense), {
          header : {
          'Content-Type' : 'multipart/form-data'
        }
        })
        .then(response => {
          this.fillTable();
          this.showNotification(response.data.notification);
        })
        .catch(error => {
          this.showNotification(error.response.data.notification);
        })
      },
      removeRow(expense) {
        // Подтверждение удален. Можно сделать кастомизированным вариантом, но для простоты оставим так
        if (!confirm('Вы уверены что хотите удалить эту строку?')) {
          return;
        }
  
        // Убрать блок визуально
        this.expenses.splice(this.expenses.indexOf(expense), 1);
        axios.delete(`/api/test/expense/${expense.id}`)
          .then(response => {
            this.showNotification(response.data.notification);
          })
          .catch(error => {
            this.showNotification(error.response.data.notification);
          })
      },
      getExpenseRowData(expense) {
        const formData = new FormData();
        formData.set('sum', expense.sum);
        formData.set('comment', expense.comment);
        formData.set('date', expense.date);
  
        return formData;
      },
      showNotification(notification) {
        Notify.create({
          type: notification.type === 'success' ? 'positive' : 'negative',
          message: notification.title
        });
      }
    }
  }
  </script>
  
  <style lang="sass" scoped>
  .expenses-list
    &__head
      display: grid
      grid-template-columns: repeat(6, 1fr)
      border-bottom: 2px solid var(--q-primary, #07e)
  
      &-item
        text-align: start
  
    &__create-button
      border: 1px solid var(--q-primary)
      font-size: 2em
      width: 2em
      height: 2em
      margin-block: 1em
      color: var(--q-primary)
      background-color: #fff
  
      &:hover
        color: #fff
        background-color: var(--q-primary)
  
  .dialog-container
    background-color: #fff
    padding: 2em
  </style>
  