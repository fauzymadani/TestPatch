#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <dirent.h>
#include <unistd.h>
#include <signal.h>

// author: fauzy madani
// this is a test repository for email based workflow
void list_processes() {
    struct dirent *entry;
    DIR *proc_dir = opendir("/proc");
    if (!proc_dir) {
        perror("Failed to open /proc directory");
        return;
    }

    printf("PID\tName\n");
    printf("----\t----------------------------\n");

    while ((entry = readdir(proc_dir)) != NULL) {
        if (entry->d_type == DT_DIR) {
            int pid = atoi(entry->d_name);
            if (pid > 0) {
                char cmdline_path[256], process_name[256];
                snprintf(cmdline_path, sizeof(cmdline_path), "/proc/%d/comm", pid);
                FILE *cmdline_file = fopen(cmdline_path, "r");
                if (cmdline_file) {
                    fgets(process_name, sizeof(process_name), cmdline_file);
                    process_name[strcspn(process_name, "\n")] = 0;  // Remove newline
                    printf("%d\t%s\n", pid, process_name);
                    fclose(cmdline_file);
                }
            }
        }
    }
    closedir(proc_dir);
}

void kill_process(int pid) {
    if (kill(pid, SIGKILL) == 0) {
        printf("Process with PID %d terminated successfully.\n", pid);
    } else {
        perror("Failed to terminate process");
    }
}

int main() {
    int choice, pid;

    while (1) {
        printf("\nTask Manager\n");
        printf("1. List Processes\n");
        printf("2. Kill Process\n");
        printf("3. Exit\n");
        printf("Enter your choice: ");
        scanf("%d", &choice);

        switch (choice) {
        case 1:
            list_processes();
            break;
        case 2:
            printf("Enter PID to terminate: ");
            scanf("%d", &pid);
            kill_process(pid);
            break;
        case 3:
            printf("Exiting...\n");
            return 0;
        default:
            printf("Invalid choice. Please try again.\n");
        }
    }

    return 0;
}
