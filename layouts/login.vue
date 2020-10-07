<template>
	<div class="d-flex">
		<div class="bg flex-grow-1">
			<div class="p-4 caption">
				<h1>Sistem Monitoring Sarana Gerbong</h1>
				<h4>Balai Yasa Tegal</h4>
			</div>
		</div>
		<Nuxt />
	</div>
</template>

<style lang="css" scoped>
.bg {
	background-image: url("/bg.jpeg");
	background-repeat: no-repeat;
	background-size: cover;
	/* color: #0e5ca9; */
	color: #fff;
}

.caption {
	position: absolute;
	bottom: 200px;
	left: 50px;
	border-left: 5px solid #fff;
	background: rgba(0, 0, 0, 0.7);
}
</style>
