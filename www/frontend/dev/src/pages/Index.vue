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
          <div class="expenses-list__head-item">Action 1</div>
          <div class="expenses-list__head-item">Action 2</div>
        </div>
      </div>
      <div class="expenses-list__body">
        <div
        v-for="expense in expenses"
        :key="expense">
          <div
            v-if="!expense.isChanges"
            class="expenses-list__row expenses-list-item"
          >
              <div class="expenses-list-item__id">{{ expense.id }}</div>
              <div class="expenses-list-item__comment">{{ expense.comment }}</div>
              <div class="expenses-list-item__sum">{{ expense.sum }}</div>
              <div class="expenses-list-item__date">{{ expense.date }}</div>
              <div class="expenses-list-item__change"><q-btn @click="changeExpense(expense)" type="button" label="Изменить" /></div>
              <div class="expenses-list-item__delete"><q-btn @click="removeExpense(expense)" type="button" label="Удалить" /></div>
          </div>
          <q-form
            v-else
            class="expenses-list__row expenses-list-item" action="#" method="POST"
            @submit="expenseFormHandler"
            @reset="expenseFormReset"
          >
              <div class="expenses-list-item__id">
                <input filled type="hidden" name="id" :value="expense.id" />{{ expense.id }}
              </div>
              <div class="expenses-list-item__comment">
                <q-input filled type="text" name="comment" label="Comment"
                  required
                  :value="expense.comment"
                  lazy-rules
                  rules="[val => val && val.length > 0 || 'Введите комментарий']"
                />
              </div>
              <div class="expenses-list-item__sum">
                <q-input filled type="number" name="sum" label="Sum"
                  required
                  :value="expense.sum"
                  lazy-rules
                  rules="[val => val && val.length > 0 || 'Введите сумму']"
                />
              </div>
              <div class="expenses-list-item__date">
                <q-input filled type="date" name="date" label="Date"
                  required
                  :value="expense.date"
                  lazy-rules
                  rules="[val => val || 'Выберите дату']" />
              </div>
              <div class="expenses-list-item__save">
                <q-btn @click="saveRow($event)" type="submit" label="Сохранить"/>
              </div>
              <div class="expenses-list-item__cancel">
                <q-btn @click="cancelChangeRow(expense)" type="reset" label="Отменить"/>
              </div>
          </q-form>
        </div>

      </div>
      <AddExpenseRow v-if="isAddingExpense" :newExpense="newExpense" @saveRow="saveNewRow($event)"></AddExpenseRow>
    </div>
    <input
      v-if="!isAddingExpense"
      @click="addExpenseRow"
      class="expenses-list__create-button" type="button" value="+" title="Добавить запись" />
  </q-page>
</template>

<script>
import axios from 'axios';
import AddExpenseRow from '../components/AddExpenseRow.vue';
import { Notify } from 'quasar';

export default {
  components: {
    AddExpenseRow
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
    saveRow(event) {
      const expenseForm = event.target.form;

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
        .catch(error => {
          const notification = error.response.data.notification;

          Notify.create({
            type: notification.type === 'success' ? 'positive' : 'negative',
            message: notification.title
          });
        });
    },
    removeExpense(expense) {
      // Подтверждение удален. Можно сделать кастомизированным вариантом, но для простоты оставим так
      if (!confirm('Вы уверены что хотите удалить эту строку?')) {
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
    display: grid
    grid-template-columns: repeat(6, 1fr)
    width: 50%
    min-width: 750px
    border-bottom: 2px solid var(--q-primary, #07e)

    &-item
      text-align: start

  &__row
      display: grid
      grid-template-columns: repeat(6, 1fr)
      width: 50%
      min-width: 750px
      margin-block: 1em
      padding-bottom: 1em
      gap: 1em
      border-bottom: 1px solid var(--q-primary, #07e)

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
