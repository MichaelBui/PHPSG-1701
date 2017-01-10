package main

import (
	redis "gopkg.in/redis.v5"
	"log"
)

const (
	REDIS_API string = "redis:6379"
	REDIS_PASSWORD string = ""
	REDIS_DATABASE int = 0
	REDIS_MESSAGE_LIST string = "log-messages"
	REDIS_LIST string = "logs"
)

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
	for {
		msg := client.BRPop(0, REDIS_MESSAGE_LIST).Val()[1]
		client.LPush(REDIS_LIST, msg)
		log.Println("Logged", msg, "to", REDIS_LIST)
	}
}
