const datetimeRelative = () => {
	const now = new Date()
	const second = 1000
	const minute = second * 60
	const hour = minute * 60
	const day = hour * 24
	const week = day * 7
	const month = day * 30
	const year = day * 365

	document.querySelectorAll("time.datetime-relative").forEach((time) => {
		const date = new Date(time.dateTime)
		let diff
		time.title = date.toString()

		if ((diff = Math.floor((now - date) / year)) > 0) {
			time.innerText = `${diff} year${diff == 1 ? "" : "s"} ago`
		} else if ((diff = Math.floor((now - date) / month)) > 0) {
			time.innerText = `${diff} month${diff == 1 ? "" : "s"} ago`
		} else if ((diff = Math.floor((now - date) / week)) > 0) {
			time.innerText = `${diff} week${diff == 1 ? "" : "s"} ago`
		} else if ((diff = Math.floor((now - date) / day)) > 0) {
			time.innerText = `${diff} day${diff == 1 ? "" : "s"} ago`
		} else if ((diff = Math.floor((now - date) / hour)) > 0) {
			time.innerText = `${diff} hour${diff == 1 ? "" : "s"} ago`
		} else if ((diff = Math.floor((now - date) / minute)) > 0) {
			time.innerText = `${diff} minute${diff == 1 ? "" : "s"} ago`
		} else if ((diff = Math.floor((now - date) / second)) > 0) {
			time.innerText = `${diff} second${diff == 1 ? "" : "s"} ago`
		} else if (Math.abs(now - date) < second) {
			time.innerText = "now"
		} else if ((diff = Math.floor((date - now) / year)) > 0) {
			time.innerText = `${diff} year${diff == 1 ? "" : "s"} from now`
		} else if ((diff = Math.floor((date - now) / month)) > 0) {
			time.innerText = `${diff} month${diff == 1 ? "" : "s"} from now`
		} else if ((diff = Math.floor((date - now) / week)) > 0) {
			time.innerText = `${diff} week${diff == 1 ? "" : "s"} from now`
		} else if ((diff = Math.floor((date - now) / day)) > 0) {
			time.innerText = `${diff} day${diff == 1 ? "" : "s"} from now`
		} else if ((diff = Math.floor((date - now) / hour)) > 0) {
			time.innerText = `${diff} hour${diff == 1 ? "" : "s"} from now`
		} else if ((diff = Math.floor((date - now) / minute)) > 0) {
			time.innerText = `${diff} minute${diff == 1 ? "" : "s"} from now`
		} else {
			time.innerText = `${diff} second${diff == 1 ? "" : "s"} from now`
		}
	})
}

setInterval(datetimeRelative, 1000)
datetimeRelative()
