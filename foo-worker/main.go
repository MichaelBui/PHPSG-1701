package main

import (
	redis "gopkg.in/redis.v5"
	"time"
	"log"
)

const (
	REDIS_API string = "redis:6379"
	REDIS_PASSWORD string = ""
	REDIS_DATABASE int = 0
	REDIS_MESSAGE_LIST string = "foo-messages"
	REDIS_LOG_LIST string = "log-messages"
)

type Entity struct {
	Name string `json:"name"`
}

var client *redis.Client

func main() {
	client = redis.NewClient(&redis.Options{
		Addr:     REDIS_API,
		Password: REDIS_PASSWORD,
		DB:       REDIS_DATABASE,
	})
	defer client.Close()
	work()
}

func work() {
	for true {
		msg := client.BRPop(0, REDIS_MESSAGE_LIST).Val()[1]
		time.Sleep(10 * time.Second)
		log.Println("Completed work with", msg)
		client.LPush(REDIS_LOG_LIST, msg + " - " + time.Now().Format("150405") + "(^_^)")
	}
}
