<template>
  <q-page padding>
    <h1>Расходы</h1>
    <table class="expenses-list" cellspacing="10">
      <thead>
        <tr class="expenses-list__head">
          <th class="expenses-list__head-item">ID</th>
          <th class="expenses-list__head-item">Comment</th>
          <th class="expenses-list__head-item">Sum</th>
          <th class="expenses-list__head-item">Date</th>
          <th class="expenses-list__head-item"></th>
          <th class="expenses-list__head-item"></th>
        </tr>
      </thead>
      <tbody class="expenses-list__tbody">
        <ExpenseRow
        v-for="expense in expenses"
        :key="expense"
        :expense="expense"
        @deletedRow="removeExpense($event)"></ExpenseRow>
        <tr
          v-if="isAddingExpense"
          class="expenses-list__row expenses-list-item expenses-list-item__create"
        >
          <td></td>
          <td><input type="text" name="newExpenseComment" v-model="newExpenseComment" /></td>
          <td><input type="number" name="newExpenseSum" v-model="newExpenseSum" /></td>
          <td><input type="date" name="newExpenseDate" v-model="newExpenseDate" /></td>
          <td><input @click="saveExpenseRow()" type="button" value="Сохранить" /></td>
        </tr>
      </tbody>
    </table>
    <input
      v-if="!isAddingExpense"
      @click="addExpenseRow"
      class="expenses-list__create-button" type="button" value="+" title="Добавить запись" />
  </q-page>
</template>

<script>
import axios from 'axios';
import ExpenseRow from '../components/ExpenseRow.vue';

export default {
  components: {
    ExpenseRow
  },
  data() {
    return {
      expenses: null,
      isAddingExpense: false,
      newExpenseComment: '',
      newExpenseSum: '',
      newExpenseDate: '',
    }
  },
  created() {
    this.fillTable();
  },
  methods: {
    fillTable() {
      axios('/api/test/expense').then(response => {
        this.expenses = response.data;
      });
    },
    addExpenseRow() {
      this.isAddingExpense = true;
    },
    saveExpenseRow() {
      this.isAddingExpense = false;

      axios.post('/api/test/expense', this.getExpenseRowData(), {
      header : {
       'Content-Type' : 'multipart/form-data'
     }
    })
        .then(response => {
          this.fillTable();
        })
        .catch(response => {
          
        });
    },
    removeExpense(expense) {
        // Убрать блок визуально
        this.$parent.$data.expenses.splice(this.$parent.$data.expenses.indexOf(expense), 1);
        axios.delete(`/api/test/expense/${expense.id}`)
            .then(() => {
            // Перезагрузка всей таблицы
            this.$parent.fillTable();
            });
    },
    getExpenseRowData() {
      const formData = new FormData();
      formData.set('sum', this.newExpenseSum);
      formData.set('comment', this.newExpenseComment);
      formData.set('date', this.newExpenseDate);

      return formData;
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
