<template>
	<div class="admin-shell">
		<Sidebar />

		<div class="main-wrapper">
			<Header />
			<div class="admin-page-content">
				<slot />
			</div>
		</div>
	</div>
</template>

<script setup>
import Header from './header.vue';
import Sidebar from './sidebar.vue';
</script>
