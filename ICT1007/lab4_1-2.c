#include <stdio.h>
#include <stdlib.h>
#include <time.h>

#define TOTAL_DISK_BLOCKS 32
#define TOTAL_DISK_INODES 8
int blockStatus[TOTAL_DISK_BLOCKS]; // free = 0
int blockList[TOTAL_DISK_BLOCKS - TOTAL_DISK_INODES]; // list of blocks of a file
struct file_table {
    char fileName[20];
    int fileSize;
    struct block *sb;
};

struct file_table fileTable[TOTAL_DISK_BLOCKS - TOTAL_DISK_INODES];
struct block {
    int blockNumber;
    struct block *next;
};
struct block blockTable[TOTAL_DISK_BLOCKS - TOTAL_DISK_INODES];

int AllocateBlocks(int Size) {
    int i = 0, count = 0;

    // check whether sufficient free blocks are available
    for (i = 0; i < (TOTAL_DISK_BLOCKS - TOTAL_DISK_INODES); i++)
        if (blockStatus[i] == 0)
            count++;
    if (count < Size)
        return 1; // not enough free blocks

    return 0;
}
void main()
{
    int i = 0, j = 0, numFiles = 0, nextBlock= 0, ret = 1;
    char s[20];
    struct block *temp = NULL;
    int allocStartBlock = TOTAL_DISK_INODES;
    int allocEndBlock = TOTAL_DISK_BLOCKS - 1;
    //Seed the pseudo-random number generator used by rand() with the value seed
    srand(time(NULL));

    printf("File Allocation Method: LINKED LIST\n");
    printf("Total blocks: %d\n", TOTAL_DISK_BLOCKS);
    printf("File allocation start at block %d\n", TOTAL_DISK_INODES);
    printf("File allocation end at block: %d\n", TOTAL_DISK_BLOCKS - 1);
    printf("Size (kB) of each block: 1\n");

    printf("Enter the number of files: ");
    scanf("%d", &numFiles);
    printf("\n");

    for (i = 0; i < numFiles; i++) {
        printf("Enter the name of file: ");
        scanf("%s", fileTable[i].fileName);

        printf("Enter the size (kB) of the file #%d: ", i+1);
        scanf("%d", &(fileTable[i].fileSize));

        printf("\n");

        ret = AllocateBlocks(fileTable[i].fileSize);
        if (ret) {
            printf("Not enough space.\n");
            exit(1);
        }

        // ALLOCATE THE BLOCKS
        while (j < fileTable[i].fileSize) {
            int k = (rand() % (allocEndBlock - allocStartBlock + 1)) + allocStartBlock;
            if (blockStatus[k] == 0) {
                blockTable[k].blockNumber = k;

                // first block
                if (fileTable[i].sb == NULL) {
                    fileTable[i].sb = &blockTable[k];
                }
                else {
                    temp->next = &blockTable[k];
                }
                temp = &blockTable[k];
                blockStatus[k] = 1;
                j++;
            }
        }
        j = 0;
    }

    // print results
    for (i = 0; i < numFiles; i++) {
        printf("%-20s %-20s %-20s\n", "FILE NAME", "FILE SIZE", "BLOCKS OCCUPIED");
        sprintf(s, "%d", fileTable[i].fileSize);
        printf("%-20s %-20s ", fileTable[i].fileName, s);

        temp = fileTable[i].sb;
        do {
            sprintf(s, "%d", temp->blockNumber);
            printf("%s", s);
            temp = temp->next;
            if (temp != NULL) {
                printf("-");
            }
        }
        while (temp != NULL);
        printf("\n");
    }
    printf("File allocation completed. Exiting.\n");
}
