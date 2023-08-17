<template>
	<div>
		<div
		v-if="!$props.expense.isChanges"
		class="expenses-list__row expenses-list-item"
		>
			<div class="expenses-list-item__id">{{ $props.expense.id }}</div>
			<div class="expenses-list-item__comment">{{ $props.expense.comment }}</div>
			<div class="expenses-list-item__sum">{{ $props.expense.sum }}</div>
			<div class="expenses-list-item__date">{{ $props.expense.date }}</div>
			<div class="expenses-list-item__change">
				<q-btn @click="changeRow($props.expense)" type="button" label="Изменить" />
			</div>
			<div class="expenses-list-item__delete">
				<q-btn @click="this.$emit('deleteRow', $props.expense)" type="button" label="Удалить" />
			</div>
		</div>
		<q-form
		v-else
		class="expenses-list__row expenses-list-item" action="#" method="POST"
		ref="expenseForm"
		@submit.prevent="expenseFormHandler"
		@reset="expenseFormReset"
		>
			<div class="expenses-list-item__id">
				<input filled type="hidden" name="id" :value="$props.expense.id" />{{ $props.expense.id }}
			</div>
			<div class="expenses-list-item__comment">
			<q-input filled type="text" name="comment" label="Comment"
				required
				v-model="$props.expense.comment"
				lazy-rules
				:rules="[val => val && val.length > 0 || 'Введите комментарий']"
			/>
			</div>
			<div class="expenses-list-item__sum">
			<q-input filled type="number" name="sum" label="Sum"
				required
				v-model="$props.expense.sum"
				lazy-rules
				:rules="[val => val && val > 0 || 'Введите сумму']"
			/>
			</div>
			<div class="expenses-list-item__date">
			<q-input filled type="date" name="date" label="Date"
				required
				v-model="$props.expense.date"
				lazy-rules
				:rules="[val => val && val.length > 0 || 'Укажите дату']" />
			</div>
			<div class="expenses-list-item__save">
				<q-btn type="submit" label="Сохранить"/>
			</div>
			<div class="expenses-list-item__cancel">
				<q-btn @click="cancelChangeRow($props.expense)" type="reset" label="Отменить"/>
			</div>
		</q-form>
	</div>
</template>

<script>
import axios from 'axios';
import { Notify } from 'quasar';

export default {
	props: [
		'expense'
	],
	methods: {
		expenseFormHandler(event) {
			const expenseForm = event.target;

			axios.patch(`/api/test/expense/${expenseForm.id.value}`, this.getExpenseRowJson(expenseForm))
				.then(response => {
					this.updateRow(expenseForm);
					this.showNotification(response.data.notification);
				})
				.catch(error => {
					this.showNotification(error.response.data.notification)
				});
		},
		changeRow(expense) {
			expense.isChanges = true;
		},
		cancelChangeRow(expense) {
			expense.isChanges = false;
		},
		updateRow(expense) {
			axios.get(`/api/test/expense/${expense.id.value}`)
				.then((response) => {
					this.$props.expense = response.data;
					this.$props.expense.isChanges = false;

					this.showNotification(response.data.notification);
				})
				.catch(error => {
					this.showNotification(error.response.data.notification);
				});
		},
		getExpenseRowJson(expense) {
			return JSON.stringify({
				'id': expense.id.value,
				'sum': expense.sum.value,
				'comment': expense.comment.value,
				'date': expense.date.value
			});
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
  &__row
      display: grid
      grid-template-columns: repeat(6, 1fr)
      margin-block: 1em
      padding-bottom: 1em
      gap: 1em
      border-bottom: 1px solid var(--q-primary, #07e)

</style>