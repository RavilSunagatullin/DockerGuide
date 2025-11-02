package main

import (
    "fmt"
    "log"
    "net/http"
)

func main() {
    http.HandleFunc("/", func(w http.ResponseWriter, r *http.Request) {
        fmt.Fprintln(w, "Hello from Go running in Docker!")
    })

    log.Println("HTTP server is listening on :3000")
    log.Fatal(http.ListenAndServe(":3000", nil))
}
