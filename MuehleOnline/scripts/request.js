

export default async function Request(url, data, method = "Post") {
    return await fetch(url, {
        method: method,
        mode: "cors",
        headers: {
            "Content-Type": "application/json",
            Accept: "application/json",
        },
        body: JSON.stringify(data),
    })
        .then((response) => {
            if (response.ok) {
                return response.json();

            } else {
                return Promise.reject(response);
            }
        })

}