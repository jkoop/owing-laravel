window.renderDatetimeAbsolute = () => {
	document.querySelectorAll("time.datetime-absolute").forEach((time) => {
		const date = new Date(time.dateTime)
		const year = date.getFullYear()
		const month = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"][
			date.getMonth()
		]
		const day = date.getDate()
		const hour = (date.getHours() + "").padStart(2, "0")
		const minute = (date.getMinutes() + "").padStart(2, "0")

		time.innerText = `${year} ${month} ${day} ${hour}:${minute}`
		time.title = date
	})
}

window.renderDatetimeAbsolute()
