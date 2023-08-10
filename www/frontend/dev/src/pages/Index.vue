<template>
  <q-page padding class="column justify-center">
    <table>
      <thead>
        <tr>
          <th class="text-left">ID</th>
          <th class="text-left">Comment</th>
          <th class="text-left">Sum</th>
          <th class="text-left">Date</th>
        </tr>
      </thead>
      <tbody>
        <tr
        class="row justify-start q-gutter-md"
        v-for="expense in expenses"
        :key="expense">
          <td>{{ expense.id }}</td>
          <td>{{ expense.comment }}</td>
          <td>{{ expense.sum }}</td>
          <td>{{ expense.date }}</td>
        </tr>
      </tbody>
    </table>
  </q-page>
</template>

<script>
const axios = require('axios')

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
  }
}
</script>

<style lang="scss" scoped>
</style>
