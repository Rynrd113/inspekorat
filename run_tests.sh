#!/bin/bash

# Quick Test Runner for Inspektorat Web Application
# This is the main entry point for running all tests

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m'

echo -e "${BLUE}================================================================${NC}"
echo -e "${BLUE}    INSPEKTORAT WEB APPLICATION - COMPREHENSIVE TESTING${NC}"
echo -e "${BLUE}================================================================${NC}"

echo -e "\n${YELLOW}Pilih metode testing yang ingin dijalankan:${NC}"
echo -e "${GREEN}1. Setup environment dan jalankan semua test (Recommended)${NC}"
echo -e "${GREEN}2. Jalankan test saja (server sudah berjalan)${NC}"
echo -e "${GREEN}3. Setup environment saja${NC}"
echo -e "${GREEN}4. Jalankan test individual${NC}"
echo -e "${GREEN}5. Keluar${NC}"

echo -e "\n${YELLOW}Masukkan pilihan (1-5): ${NC}"
read -r choice

case $choice in
    1)
        echo -e "\n${BLUE}Menjalankan setup dan comprehensive testing...${NC}"
        chmod +x start_testing.sh
        ./start_testing.sh
        ;;
    2)
        echo -e "\n${BLUE}Menjalankan comprehensive testing...${NC}"
        chmod +x final_comprehensive_testing.sh
        ./final_comprehensive_testing.sh
        ;;
    3)
        echo -e "\n${BLUE}Menjalankan setup environment...${NC}"
        chmod +x setup_testing.sh
        ./setup_testing.sh
        ;;
    4)
        echo -e "\n${YELLOW}Pilih test individual:${NC}"
        echo -e "${GREEN}1. Python Frontend Tests${NC}"
        echo -e "${GREEN}2. PHP Backend Tests${NC}"
        echo -e "${GREEN}3. Security Tests${NC}"
        echo -e "${GREEN}4. Performance Tests${NC}"
        echo -e "${GREEN}5. Laravel Unit Tests${NC}"
        
        echo -e "\n${YELLOW}Masukkan pilihan (1-5): ${NC}"
        read -r test_choice
        
        case $test_choice in
            1)
                echo -e "\n${BLUE}Menjalankan Python Frontend Tests...${NC}"
                python3 automated_comprehensive_testing.py
                ;;
            2)
                echo -e "\n${BLUE}Menjalankan PHP Backend Tests...${NC}"
                php backend_comprehensive_testing.php
                ;;
            3)
                echo -e "\n${BLUE}Menjalankan Security Tests...${NC}"
                chmod +x security_testing.sh
                ./security_testing.sh
                ;;
            4)
                echo -e "\n${BLUE}Menjalankan Performance Tests...${NC}"
                chmod +x load_testing.sh
                ./load_testing.sh
                ;;
            5)
                echo -e "\n${BLUE}Menjalankan Laravel Unit Tests...${NC}"
                ./vendor/bin/phpunit --testdox
                ;;
            *)
                echo -e "\n${RED}Pilihan tidak valid!${NC}"
                ;;
        esac
        ;;
    5)
        echo -e "\n${YELLOW}Terima kasih!${NC}"
        exit 0
        ;;
    *)
        echo -e "\n${RED}Pilihan tidak valid!${NC}"
        exit 1
        ;;
esac

echo -e "\n${BLUE}================================================================${NC}"
echo -e "${BLUE}                    TESTING COMPLETED${NC}"
echo -e "${BLUE}================================================================${NC}"
