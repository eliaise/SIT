#include <stdio.h>
#include <stdlib.h>
#include <time.h>
#define TOTAL_DISK_BLOCKS 32
#define TOTAL_DISK_INODES 8
int blockStatus[TOTAL_DISK_BLOCKS]; // free = 0
int blockStart;
struct file_table {
    char fileName[20];
    int startBlock;
    int fileSize;
    int allotStatus;
};
struct file_table fileTable[TOTAL_DISK_BLOCKS - TOTAL_DISK_INODES];

int AllocateBlocks(int Size) {
    int i = 0, count = 0, inList = 0, nextBlock = 0;
    int allocStartBlock = TOTAL_DISK_INODES;
    int allocEndBlock = TOTAL_DISK_BLOCKS - 1;

    // check whether sufficient free blocks are available
    for (i = 0; i < (TOTAL_DISK_BLOCKS - TOTAL_DISK_INODES); i++)
        if (blockStatus[i] == 0)
            count++;
    if (count < Size)
        return 1; // not enough free blocks
    count = 0;

    // check if there are any sequential blocks of the size needed
    while (count < Size) {
        nextBlock = (rand() % (allocEndBlock - allocStartBlock + 1)) + allocStartBlock;
        for (i = nextBlock; i < (nextBlock + Size); i++)
        {
            if (blockStatus[i] == 0)
                count = count + 1;
            else {
                count = 0;
                break;
            }
        }
    }
    blockStart = nextBlock;
    if (count == Size)
        return 0; // success
    else
        return 1; // not successful
}

void main() {
    int i = 0, j = 0, numFiles = 0, nextBlock = 0, ret = 1;
    char s[20];
    //Seed the pseudo-random number generator used by rand() with the value seed
    srand(time(NULL));

    printf("File Allocation Method: SEQUENTIAL\n");
    printf("Total blocks: %d\n", TOTAL_DISK_BLOCKS);
    printf("File allocation start at block %d\n", TOTAL_DISK_INODES);
    printf("File allocation end at block: %d\n", TOTAL_DISK_BLOCKS - 1);
    printf("Size (kB) of each block: 1\n");

    printf("\n");

    printf("Enter the number of files: ");
    fgets(s, 20, stdin);
    printf("\n");
    numFiles = atoi(s);
    if (numFiles < 1) {
        printf("Number of files invalid.\n");
        exit(1);
    }

    for (i = 0; i < numFiles; i++) {
        printf("Enter the name of file: ");
        scanf("%s", fileTable[i].fileName);

        printf("Enter the size (kB) of the file #%d: ", i+1);
        scanf("%d", &(fileTable[i].fileSize));

        printf("\n");

        ret = AllocateBlocks(fileTable[i].fileSize);
        if (ret) {
            printf("Failed to allocate.\n");
            exit(1);
        }
        fileTable[i].startBlock = blockStart;

        // perform the allocation
        for (j = blockStart; j <= blockStart + fileTable[i].fileSize; j++) {
            blockStatus[i] = 1;
        }

        fileTable[i].allotStatus = ret;
    }

    printf("\n");

    // print output
    for (i = 0; i < numFiles; i++) {
        printf("%-20s %-20s %-20s\n", "FILE NAME", "FILE SIZE", "BLOCKS OCCUPIED");
        sprintf(s, "%d", fileTable[i].fileSize);
        printf("%-20s %-20s ", fileTable[i].fileName, s);

        sprintf(s, "%d", fileTable[i].startBlock);
        printf("%s", s);
        for (j = fileTable[i].startBlock + 1; j <= fileTable[i].fileSize - 1 + fileTable[i].startBlock; j++) {
            printf("-");
            sprintf(s, "%d", j);
            printf("%s", s);
        }
        printf("\n");
    }
    printf("File allocation completed. Exiting.\n");
}
