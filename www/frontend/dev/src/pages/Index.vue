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
          <th class="expenses-list__head-item">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr
        class="expenses-list__row expenses-list-item"
        v-for="expense in expenses"
        :key="expense">
          <td class="expenses-list-item__id">{{ expense.id }}</td>
          <td class="expenses-list-item__comment">{{ expense.comment }}</td>
          <td class="expenses-list-item__sum">{{ expense.sum }}</td>
          <td class="expenses-list-item__date">{{ expense.date }}</td>
          <td class="expenses-list-item__actions"><input @click="apiRemoveItem(expense)" type="button" value="Удалить" /></td>
        </tr>
      </tbody>
    </table>
  </q-page>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      expenses: null
    }
  },
  mounted() {
    axios('/api/test/expense').then(response => {
      this.expenses = response.data;
    });
  },
  methods: {
    apiRemoveItem(expense) {
      this.expenses.splice(this.expenses.indexOf(expense), 1);
      axios.delete(`/api/test/expense/${expense.id}`);
    }
  }
}
</script>

<style lang="sass" scoped>
.expenses-list

  &__head
    &-item
      text-align: start

</style>
