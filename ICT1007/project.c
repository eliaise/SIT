#include <stdio.h>
#include <stdlib.h>
#include <regex.h>
#include <string.h>
#include <math.h>

typedef struct Process {
    float burstTime;                                    // initial burst time
    float remainingTime;                                // value to be modified when given CPU time
    int arrivalOrder;                                   // used in the event that arrivalTime and burstTime are the same
    int arrivalTime;                                    // arrival time of the process
    float weight;                                       // potentially used to indicate "priority" of sorts
    float waitingTime;                                  // time spent waiting since last given CPU time
    float turnaroundTime;                               // time finished execution - arrivalTime
    float totalWaitingTime;                             // total time spent waiting
    struct Process *next;                               // points to the process next in queue (0 -> 1)
    struct Process *previous;                           // points to the process in front of it (0 -> -1)
} Process;

Process *readyQueue;                                    // points to end of queue

Process *futuresQueue;                                  // points to the tail of processes yet to arrive
Process *finishedQueue;                                 // points to the tail of processes that finished execution

const char* PATTERN_STRING = "^([0-9]+)[ ]([0-9]+)\n$";
const int BUFFER_SIZE = 100;
const int REGEX_MATCH = 3;

int queueNumber = 1;

void readFile(char *file);
void createProcess(float burstTime, int arrivalTime);
void enqueue(Process *p, Process **end);
void dequeue(Process **priorProcess);
void printQueue(Process **end);
void printResult(Process **end);
void hrrrn();

float avgTurn, avgWait, maxTurn, maxWait = 0.0;

int testBurstTimes[5] = {35,16,24,1,10};    // for testing purposes

int main(int argc, char* argv[]) {
    if (argc != 2) {
        printf("Usage: %s <file_path>\n", argv[0]);
        return 1;
    }

    readFile(argv[1]);
    //printQueue(&futuresQueue);

    while (futuresQueue) {
        hrrrn();
    }
    printf("=======================\nCompleted: \n");
    printQueue(&finishedQueue);

    printf("=======================\nResults: \n");
    printResult(&finishedQueue);

    /*
    for (int i = 0; i < 5; i++) {
        Process *p = (Process*) malloc(sizeof(Process));
        p->arrivalOrder = queueNumber++;
        p->burstTime = testBurstTimes[i];
        enqueue(p, &tail);
    }
    printQueue(&tail);
    printf("=================================\n");

    // example dequeue process
    Process *current = NULL;                            // 1 -> 2 -> 3 -> 4 -> 5 -> 1 -> ...
    current = tail->next;                               // dequeue 2
    dequeue(current, &tail);                            // 1 -> 3 -> 4 -> 5 -> 1 -> ...
    printQueue(&tail);                                  // note that the tail doesn't change
    */

    return 0;
}

/*
 * Reads the input file and calls the createProcess() function.
 */
void readFile(char *file) {
    char buffer[BUFFER_SIZE];
    char line[BUFFER_SIZE];
    FILE *fp = NULL;
    regex_t pattern;
    regmatch_t groups[REGEX_MATCH];
    regcomp(&pattern, PATTERN_STRING, REG_EXTENDED);
    int processBurstTime;
    int processArrivalTime;


    fp = fopen(file, "r");
    if (!fp) {
        printf("Error reading file.\n");
        exit(1);
    }

    while (fgets(line, BUFFER_SIZE, fp)) {
        char *linePtr = line;
        if (regexec(&pattern, line, REGEX_MATCH, groups, 0)) {
            break;
        }
        strncpy(buffer, linePtr, groups[1].rm_eo - groups[1].rm_so);
        buffer[groups[1].rm_eo - groups[1].rm_so] = '\0';
        processArrivalTime = atoi(buffer);

        strncpy(buffer, linePtr + groups[2].rm_so, groups[2].rm_eo - groups[2].rm_so);
        buffer[groups[2].rm_eo - groups[2].rm_so] = '\0';
        processBurstTime = atof(buffer);

        createProcess(processBurstTime, processArrivalTime);
    }

    if (fclose(fp)) {
        printf("Error closing file.\n");
        exit(1);
    }
}

/*
 * Creates and queues the process into the futuresQueue
 * Parameters:
 *      burstTime - burst time of the process
 *      arrivalTime - arrival time of the process
 */
void createProcess(float burstTime, int arrivalTime) {
    Process *p = (Process *)malloc(sizeof(Process));
    p->burstTime = p->remainingTime = burstTime;
    p->arrivalOrder = queueNumber++;
    p->arrivalTime = arrivalTime;
    p->turnaroundTime = p->waitingTime = p->totalWaitingTime = 0;
    p->weight = 1.0;

    enqueue(p, &futuresQueue);
}

/*
 * Place the process at the end of the queue.
 * Parameters:
 *      p - the process to queue
 *      end - the tail of the queue
 */
void enqueue(Process *p, Process **end) {
    if (*end == NULL) {                                 // queue is empty
        *end = p;
        p->next = p;
    }
    else {
        p->next = (*end)->next;
        (*end)->next = p;
        *end = p;
    }
}

/*
 * Remove the process from the queue.
 * Parameters:
 *      priorProcess - the process in front of the one to be deleted
 *      E.g.
 *          [ 1 ] -> [ 2 ] -> [ 3 ]         // delete [ 2 ], then priorProcess is [ 1 ]
 */
void dequeue(Process **priorProcess) {
    if ((*priorProcess)->next == *priorProcess) {               // priorProcess is the last element in queue
        *priorProcess = NULL;
    }
    else {
        (*priorProcess)->next = (*priorProcess)->next->next;    // the element before p points to the element after p
    }
}

/*
 * Prints the elements in the queue.
 * Parameters:
 *      end: the tail of the queue
 */
void printQueue(Process **end) {
    Process *current = *end;
    do {
        current = current->next;
        printf("Process: %d\nBurst Time: %.2f\nTurnaround Time: %.2f\nWaiting Time: %.2f\n\n",
               current->arrivalOrder, current->burstTime, current->turnaroundTime, current->totalWaitingTime);
    } while (current != *end);
}

/*
 * Prints the result for avg and max turnaround time, and waiting time
 * Parameters:
 *      end: the tail of the queue
 */
void printResult(Process **end) {
    Process *current = *end;
    int count = 0;
    do {
        current = current->next;
        if (current->turnaroundTime > maxTurn){
            maxTurn = current->turnaroundTime;
        }
        if (current->totalWaitingTime > maxWait){
            maxWait = current->totalWaitingTime;
        }
        avgTurn += current->turnaroundTime;
        avgWait += current->totalWaitingTime;
        count++;
    } while (current != *end);

    printf("Count: %.d\n",count);
    printf("Average Turnaround Time: %.2f\n", avgTurn/count);
    printf("Maximum Turnaround Time: %.2f\n", maxTurn);
    printf("Average Waiting Time: %.2f\n", avgWait/count);
    printf("Maximum Waiting Time: %.2f\n", maxWait);
}

/*
 * Performs the HRRN + RR algorithm.
 */
void hrrrn() {
    int timeElapsed = futuresQueue->next->arrivalTime;          // CASE 1: first process' arrival time != 0
    int quantumTime;
    int queueCount = 0;
    int totalBurstTime = 0;                                     // of the processes in queue

    // Get the first processes at time = 0
    // Sort by SJF
    Process *current = futuresQueue->next;
    Process *temp = readyQueue;                                 // used to store the index to insert the process
    Process *next = NULL;
    while (current->arrivalTime == timeElapsed && futuresQueue != NULL) {
        next = current->next;
        dequeue(&futuresQueue);                      // remove current from futuresQueue
        if (readyQueue == NULL || readyQueue->burstTime < current->burstTime) {
            enqueue(current, &readyQueue);              // enqueue and update the tail position
        }
        else {
            while (temp->next->burstTime <= current->burstTime) {
                temp = temp->next;
            }
            enqueue(current, &temp);                    // enqueue, but no need to update the tail position
        }

        queueCount++;
        totalBurstTime += current->burstTime;

        temp = readyQueue;
        current = next;
    }

    // Run processes
    int timeTaken;                                           // used to store the waiting time for other processes
    while (readyQueue) {
        current = readyQueue->next;
        if (queueCount > 1) {
            // Quantum time is 66% of average burst time
            // quantumTime = floor(totalBurstTime / queueCount / 1.5f);
            // quantumTime = totalBurstTime / queueCount / 1.5f;

            // Quantum time is average burst time
            quantumTime = 1.0f * totalBurstTime / queueCount;
        }
        // Special Clause: if queueCount == 1, and other processes arrive during execution of current,
        //                 execute current until the arrival of next process, then recalculate quantumTime
        // Reason: this is to prevent this process, which may be a long process, from hogging the CPU
        else if (queueCount == 1 && futuresQueue && futuresQueue->next->arrivalTime < timeElapsed + current->remainingTime) {
            quantumTime = futuresQueue->next->arrivalTime - timeElapsed;
        }
        else {
            quantumTime = current->remainingTime;
        }

        printf("======\nAverage Burst Time: %.2f\n", 1.0f * totalBurstTime / queueCount);

        next = current->next;

        // Special Clause: if remaining time after given quantum time is lesser than 20% of quantum time, then just finish
        //  || current->remainingTime - quantumTime <= 0.2f * quantumTime
        // Reason: more processes waiting = more total waiting time
        if (current->remainingTime <= quantumTime) {
            dequeue(&readyQueue);                 // remove process from readyQueue
            queueCount--;
            timeTaken = current->remainingTime;

            current->turnaroundTime = timeElapsed + timeTaken - current->arrivalTime;

            enqueue(current, &finishedQueue);        // add to finishedQueue
        }
        else {
            timeTaken = quantumTime;
        }
        timeElapsed += timeTaken;
        totalBurstTime -= timeTaken;

        printf("Currently Executing: Process %d, %.2f\n", current->arrivalOrder, current->remainingTime);

        current->remainingTime -= timeTaken;
        current->totalWaitingTime += current->waitingTime;
        current->waitingTime = 0;

        printf("Time Taken: %d\n", timeTaken);
        printf("Quantum Time: %d\n", quantumTime);

        // Add the timeTaken to the waitingTime of the processes in readyQueue, except current process
        // Refer to DO WHILE block on line 263
        if (current != next) {
            temp = next;
            do {
                temp->waitingTime += timeTaken;
                temp = temp->next;
            }
            while (temp != readyQueue->next);               // readyQueue->next may be current process, or next process
        }

        // Check whether other process have joined the queue during the timeTaken for the current process to run
        while (futuresQueue && futuresQueue->next->arrivalTime <= timeElapsed) {
            temp = futuresQueue->next;
            dequeue(&futuresQueue);

            enqueue(temp, &readyQueue);
            temp->waitingTime = timeElapsed - temp->arrivalTime;
            queueCount++;
            totalBurstTime += temp->burstTime;
        }

        if (readyQueue) {
            // CASE 3: the tail doesn't change if the current process finished execution, because current is now in the
            //         finished queue
            if (finishedQueue == current) {
                // do nothing
            }
            else {
                readyQueue = current;
            }
            next = readyQueue->next;

            // Calculate Response Ratio for all processes in readyQueue, including current process if it didn't finish
            temp = next;
            do {
                temp->weight = (temp->waitingTime + temp->remainingTime) / temp->remainingTime;
                temp = temp->next;
            }
            while (temp != next);

            // Sort Response Ratio in descending order
            current = next;                 // points to unsorted
            readyQueue = NULL;              // points to sorted
            temp = NULL;                    // points to index on where to insert into sorted
            int sortedCount = 0;
            while (sortedCount != queueCount) {
                next = current->next;

                // Possible scenarios whereby current becomes the tail:
                // 1. readyQueue is empty
                // 2. current has lesser weight than the last process in the readyQueue
                // 3. current has the same weight, but longer remaining time than the last process in the readyQueue
                // 4. current has the same weight, same remaining time but arrives later than the last process in the readyQueue
                if (readyQueue == NULL
                || readyQueue->weight > current->weight
                || (readyQueue->weight == current->weight && current->remainingTime > readyQueue->remainingTime)
                || (readyQueue->weight == current->weight && current->remainingTime == readyQueue->remainingTime
                    && current->arrivalOrder > readyQueue->arrivalOrder))
                {
                    enqueue(current, &readyQueue);
                }
                else {
                    while (temp->next->weight >= current->weight) {
                        // if same response ratio, then the one with lower remaining burst time goes first
                        if (temp->next->weight == current->weight) {
                            if (temp->next->remainingTime > current->remainingTime) {
                                break;
                            }
                            // if same remaining time, then the one that arrived first will go first
                            else if (temp->next->remainingTime == current->remainingTime) {
                                if (temp->next->arrivalOrder > current->arrivalOrder) {
                                    break;
                                }
                                // CASE 2: there is only 1 process, process A, in the sorted queue, and the current
                                //         process we're trying to insert, Process B, is identical to process A except
                                //         that process B arrived later
//                                else if (temp == temp->next) {
//                                    readyQueue = current;
//                                    break;
//                                }
                            }
                            // temp->next->remainingTime < current->remainingTime
                            else {
                                if (temp->next->remainingTime > current->remainingTime) {
                                    break;
                                }
                            }
                        }
                        temp = temp->next;
                    }
                    enqueue(current, &temp);
                }

                temp = readyQueue;
                current = next;
                sortedCount++;
            }
        }
    }
}
