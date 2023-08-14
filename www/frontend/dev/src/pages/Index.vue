<template>
  <q-page padding>
    <h1>Расходы</h1>
    <div class="expenses-list">
      <div>
        <div class="expenses-list__head">
          <div class="expenses-list__head-item">ID</div>
          <div class="expenses-list__head-item">Comment</div>
          <div class="expenses-list__head-item">Sum</div>
          <div class="expenses-list__head-item">Date</div>
          <div class="expenses-list__head-item"></div>
          <div class="expenses-list__head-item"></div>
        </div>
      </div>
      <div class="expenses-list__tbody">
        <ExpenseRow
        v-for="expense in expenses"
        :key="expense"
        :expense="expense"
        @changesRow="changeExpense($event)"
        @deletedRow="removeExpense($event)"
        @saveRow="saveRow($event)"
        @cancelChangeRow="cancelChangeRow($event)"></ExpenseRow>
        <div
          v-if="isAddingExpense"
          class="expenses-list__row expenses-list-item expenses-list-item__create"
        >
          <div></div>
          <div><input type="text" name="newExpenseComment" v-model="newExpense.comment" /></div>
          <div><input type="number" name="newExpenseSum" v-model="newExpense.sum" /></div>
          <div><input type="date" name="newExpenseDate" v-model="newExpense.date" /></div>
          <div><input @click="saveNewRow(newExpense)" type="button" value="Сохранить" /></div>
        </div>
      </div>
    </div>
    <input
      v-if="!isAddingExpense"
      @click="addExpenseRow"
      class="expenses-list__create-button" type="button" value="+" title="Добавить запись" />
  </q-page>
</template>

<script>
import axios from 'axios';
import ExpenseRow from '../components/ExpenseRow.vue';
import { Notify } from 'quasar';

export default {
  components: {
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
      axios('/api/test/expense').then(response => {
        this.expenses = response.data.map(expense => {
          expense.isChanges = false; // Добавляем свойство isChanges для каждого расхода

          return expense;
        });
      })
      .then(error => {
        const notification = error.response.data.notification;

        Notify.create({
          type: notification.type === 'success' ? 'positive' : 'negative',
          message: notification.title
        });
      });
    },
    addExpenseRow() {
      this.isAddingExpense = true;
    },
    saveRow(expenseForm) {
      axios.patch(`/api/test/expense/${expenseForm.id.value}`, this.getExpenseRowJson(expenseForm))
        .then(response => {
          this.updateExpense(expenseForm);
          const notification = response.data.notification;

          Notify.create({
            type: notification.type === 'success' ? 'positive' : 'negative',
            message: notification.title
          });
        })
        .catch(error => {
          const notification = error.response.data.notification;

          Notify.create({
            type: notification.type === 'success' ? 'positive' : 'negative',
            message: notification.title
          });
        });
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
          const notification = response.data.notification;

          Notify.create({
            type: notification.type === 'success' ? 'positive' : 'negative',
            message: notification.title
          });
        })
        .catch(error => {
            const notification = error.response.data.notification;

            Notify.create({
              type: notification.type === 'success' ? 'positive' : 'negative',
              message: notification.title
            });
        });
    },
    updateExpense(expense) {
      axios.get(`/api/test/expense/${expense.id.value}`)
        .then((response) => {
          const index = this.findExpenseIndexById(expense.id.value);

          this.expenses[index] = response.data;
          this.expenses[index].isChanges = false;
          const notification = response.data.notification;

          Notify.create({
            type: 'positive',
            message: notification.title
          })
        })
        .then(error => {
          const notification = error.response.data.notification;

          Notify.create({
            type: notification.type === 'success' ? 'positive' : 'negative',
            message: notification.title
          });
        });
    },
    removeExpense(expense) {
      if (!confirm('Вы уверены что хотите удалить эту строку?')) { // Подтверждение
        return;
      }

      // Убрать блок визуально
      this.expenses.splice(this.expenses.indexOf(expense), 1);
      axios.delete(`/api/test/expense/${expense.id}`)
        .then(response => {
          const notification = response.data.notification;

          Notify.create({
            type: 'positive',
            message: notification.title
          });
        })
        .catch(error => {
          const notification = error.response.data.notification;

          Notify.create({
            type: notification.type === 'success' ? 'positive' : 'negative',
            message: notification.title
          });
        })
        
    },
    changeExpense(expense) {
      expense.isChanges = true;
    },
    cancelChangeRow(expense) {
      expense.isChanges = false;
    },
    getExpenseRowJson(expense) {
      return JSON.stringify({
        'id': expense.id.value,
        'sum': expense.sum.value,
        'comment': expense.comment.value,
        'date': expense.date.value
      });
    },
    getExpenseRowData(expense) {
      const formData = new FormData();
      formData.set('sum', expense.sum);
      formData.set('comment', expense.comment);
      formData.set('date', expense.date);

      return formData;
    },
    findExpenseIndexById(expenseId) {
      return this.expenses.findIndex(expense => +expense.id === +expenseId)
    }
  }
}
</script>

<style lang="sass" scoped>
.expenses-list

  &__head
    &-item
      text-align: start

  &__create-button
    border: 1px solid var(--q-primary)
    font-size: 2em
    width: 2em
    height: 2em
    color: var(--q-primary)
    background-color: #fff

    &:hover
      color: #fff
      background-color: var(--q-primary)

</style>
